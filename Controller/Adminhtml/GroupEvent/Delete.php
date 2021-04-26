<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_EventManager
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\EventManager\Controller\Adminhtml\GroupEvent;

use Magento\Framework\Controller\ResultFactory;

/**
 * Delete class is a controller to delete event
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\GroupEventFactory
     */
    protected $groupEvent;
    
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageModel;
    
    /**
     * @param \Magento\Backend\App\Action\Context          $context
     * @param \Magento\Framework\Message\ManagerInterface  $messageManager
     * @param \Webkul\EventManager\Model\GroupEventFactory $GroupEventfactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Webkul\EventManager\Model\GroupEventFactory $GroupEventfactory,
        \Magento\Cms\Model\PageFactory $pageFactory
    ) {
        $this->groupEvent = $GroupEventfactory;
        $this->pageModel = $pageFactory;
        parent::__construct($context);
    }
    
    /**
     * _isAllowed is used to authorize role
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_EventManager::EventManager');
    }
    
    /**
     * Delete group events
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $groupEvent = $this->groupEvent->create();
        /*
        * Here single delete action performe
        */
        if (isset($data['group_event_id'])) {
            try {
                $this->removeIdFromPages($data['group_event_id']);
                $groupEvent->load($data['group_event_id'])->delete();
                $this->messageManager->addSuccess(__("successfully deleted"));
            } catch (\excetion $e) {
                $this->messageManager->addError(__("Data not Deleted , some error happen"));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('eventmanager/groupevent/groupeventpage');
    }
    
    /**
     * To removing event group id from the pages where event grouop id was assigned.
     *
     * @param  int $groupEventId
     * @return void
     */
    public function removeIdFromPages($groupEventId)
    {
        $pageData = $groupEvent = $this->groupEvent->create()->load($groupEventId)->getShowOnPage();
        $pageIds = explode(",", $pageData);
        $collection = $this->pageModel->create()->getCollection()->addFieldToFilter('page_id', ['in' => $pageIds]);
        foreach ($collection as $data) {
            $content = $data->getContent();
            if (preg_match("/{{block\sclass.{2}Webkul.EventManager(.*?)}}/", $content)) {
                preg_match_all('/groupId="(.*?)"/', $content, $match);
                if ($match) {
                    $ids = $match['1']['0'];
                    $ids = explode(",", $ids);
                    $remainIds = [];
                    foreach ($ids as $idValue) {
                        if ($idValue != $groupEventId) {
                            $remainIds[] = $idValue;
                        }
                    }
                    $replaceData = 'groupId='.'"'.implode(",", $remainIds).'"';
                    $alterData = preg_replace('/groupId="(.*)(")/', $replaceData, $content);
                    $data->setData('content', $alterData);
                    $data->save();
                }
            }
        }
    }
}
