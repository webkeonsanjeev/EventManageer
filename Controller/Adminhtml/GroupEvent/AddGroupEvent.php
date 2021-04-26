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
 
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * AddGroupEvent class is a controller ro add group events
 */
class AddGroupEvent extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Webkul\EventManager\Model\ResourceModel\GroupEvent\CollectionFactory
     */
    protected $groupEvent;

    /**
     * @param \Magento\Backend\App\Action\Context          $context
     * @param \Magento\Framework\Registry                  $registry
     * @param \Webkul\EventManager\Model\GroupEventFactory $groupeventfactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Webkul\EventManager\Model\GroupEventFactory $groupeventfactory
    ) {
        $this->groupEvent = $groupeventfactory;
        $this->registry = $registry;
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
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = '';
        $groupEvent = $this->groupEvent->create();
        $groupevent_id = $this->getRequest()->getParam('group_event_id');
        if ($groupevent_id) {
            $id = $groupevent_id;
            $this->registry->register('group_event_id', $groupevent_id);
            $groupEvent = $groupEvent->load($id);
        }
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Webkul_EventManager::EventManager');
        $resultPage->getConfig()->getTitle()->prepend(__('Group Event info'));
        $resultPage->getConfig()->getTitle()->prepend(
            $groupEvent->getId() ? __(ucfirst($groupEvent->getGroupName())): __('New Group Event')
        );
        return $resultPage;
    }
}
