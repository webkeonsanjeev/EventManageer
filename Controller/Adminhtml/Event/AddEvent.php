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

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * AddEvent class is a controller to add new event
 */
class AddEvent extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\EventFactory
     */
    protected $event;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Backend\App\Action\Context     $context
     * @param \Webkul\EventManager\Model\EventFactory $eventFactory
     * @param \Magento\Framework\Registry             $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Webkul\EventManager\Model\EventFactory $eventFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->event = $eventFactory;
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
     * Event add controller
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = '';
        $event = $this->event->create();
        $data = $data = $this->getRequest()->getParam('event_id');
		
		
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/updateevent.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);

$logger->debug('data =  ' . print_r($data,1) . '<br>');	
		
		
        if ($data) {
            $id = $data;
            $event = $event->load($id);
            $this->registry->register('eventId', $data);
        }
        
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Webkul_EventManager::EventManager');
        $resultPage->getConfig()->getTitle()->prepend(__('Event info'));
        $resultPage->getConfig()->getTitle()->prepend($id ? __(ucfirst($event->getEventName())) : __('New Event Form'));
        return $resultPage;
    }
}
