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

use \Magento\Backend\App\Action\Context;

/**
 * MassStatus class for performing mass action in admin event manager event page grid
 */
class MassStatus extends \Magento\Backend\App\Action
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
     * __construct function
     *
     * @param \Magento\Backend\App\Action\Context     $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Webkul\EventManager\Model\EventFactory $eventFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Webkul\EventManager\Model\EventFactory $eventFactory
    ) {
         $this->event = $eventFactory;
         $this->filter = $filter;
         parent::__construct($context);
    }

    /**
     * Change status of event
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
          $check = 0;
          $event = $this->event->create();
          $collection = $this->filter->getCollection($event->getCollection());
          $eventIds = $collection->getAllIds();
          $data = $this->getRequest()->getParams();
        if (isset($eventIds) && isset($data['status'])) {
            $status = $data['status'];
            foreach ($eventIds as $value) {
                 $model = $event->load($value);
                 $model->setData('event_status', $status)->save();
                 $check=1;
            }
        }
        if ($check==1) {
             $this->messageManager->addSuccess("Successfully Status Changed");
        } else {
                $this->messageManager->addError(" Status Not Changed");
        }
          $resultRedirect = $this->resultRedirectFactory->create();
             return $resultRedirect->setPath('eventmanager/event/eventpage');
    }
}
