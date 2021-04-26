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
 * MassDelete class is a controller to perform mass action delete on selected events
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\EventFactory
     */
    protected $event;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \Webkul\EventManager\Helper\Data
     */
    protected $helper;
    
    /**
     * __construct function
     *
     * @param \Magento\Backend\App\Action\Context     $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Webkul\EventManager\Model\EventFactory $Eventfactory
     * @param \Webkul\EventManager\Helper\Data        $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Webkul\EventManager\Model\EventFactory $Eventfactory,
        \Webkul\EventManager\Helper\Data $helper
    ) {
        $this->helper = $helper;
        $this->event = $Eventfactory;
        $this->filter = $filter;
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
     * Mass delete events
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $check = 0;
        $event = $this->event->create();
        $collection = $this->filter->getCollection($event->getCollection());
        $eventIds = $collection->getAllIds();
        /*
        * Here multiple delete action performe
        */
        if ($eventIds) {
            try {
                foreach ($eventIds as $value) {
                    if (count($groupEventIds = $this->helper->checkEventId($value))) {
                        $this->messageManager->addError(__(
                            "Please first remove Event with id = %1 from Group Events whose id(s) = %2",
                            $value,
                            implode(",", $groupEventIds)
                        ));
                    } else {
                        $event->load($value)->delete();
                        $check =1;
                    }
                }
            } catch (\excetion $e) {
                
                $this->messageManager->addError(__("Data not Deleted , some error happen"));
            }
        }
        if ($check == 1) {
            $this->messageManager->addSuccess(__("successfully deleted"));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('eventmanager/event/eventpage');
    }
}
