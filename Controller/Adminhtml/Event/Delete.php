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
namespace Webkul\EventManager\Controller\Adminhtml\Event;

use Magento\Framework\Controller\ResultFactory;

/**
 * Delete class is a controller to delete event
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\EventFactory
     */
    protected $event;

    /**
     * @var \Webkul\EventManager\Helper\Data
     */
    protected $helper;
    
    /**
     * @param \Magento\Backend\App\Action\Context     $context
     * @param \Webkul\EventManager\Model\EventFactory $Eventfactory
     * @param \Webkul\EventManager\Helper\Data        $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Webkul\EventManager\Model\EventFactory $Eventfactory,
        \Webkul\EventManager\Helper\Data $helper
    ) {
        $this->helper = $helper;
        $this->event = $Eventfactory;
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
     * Delete event controller
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $event = $this->event->create();
        /*
        * Here single delete action performe
        */
        if (isset($data['event_id'])) {
            if (count($groupEventIds = $this->helper->checkEventId($data['event_id']))) {
                $this->messageManager->addError(__(
                    "Please first remove Event from Group Events whose id(s) are %1",
                    implode(",", $groupEventIds)
                ));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('eventmanager/event/eventpage');
            }
            try {
                $event->load($data['event_id'])->delete();
                $this->messageManager->addSuccess(__("successfully deleted"));
            } catch (\excetion $e) {
                $this->messageManager->addError(__("Data not Deleted , some error happen"));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('eventmanager/event/eventpage');
    }
}
