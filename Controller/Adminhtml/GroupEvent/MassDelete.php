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
 * MassDelete class is a controller to perform mass action delete on selected events
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\GroupEventFactory
     */
    protected $groupEvent;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;
    
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageModel;
    
    /**
     * @param \Magento\Backend\App\Action\Context          $context
     * @param \Magento\Ui\Component\MassAction\Filter      $filter
     * @param \Webkul\EventManager\Model\GroupEventFactory $GroupEventfactory
     * @param \Magento\Cms\Model\PageFactory               $pageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Webkul\EventManager\Model\GroupEventFactory $GroupEventfactory,
        \Magento\Cms\Model\PageFactory $pageFactory
    ) {
        $this->groupEvent = $GroupEventfactory;
        $this->filter = $filter;
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
     * Mass delete group events
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $check = 0;
        $data = $this->getRequest()->getParams();
        $groupEvent = $this->groupEvent->create();
        $collection = $this->filter->getCollection($groupEvent->getCollection());
        $groupEventIds = $collection->getAllIds();
        /*
        * Here single delete action performe
        */
        if ($groupEventIds) {
            try {
                foreach ($groupEventIds as $id) {
                    $this->removeIdFromPages($id);
                }
                $groupEvent->getCollection()
                    ->addFieldToFilter("group_event_id", ['in' => $groupEventIds])->walk('delete');
                $check = 1;
            } catch (\excetion $e) {
                $this->messageManager->addError(__("Data not Deleted , some error happen"));
            }
        }
        if ($check == 1) {
            $this->messageManager->addSuccess(__("successfully deleted"));
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
