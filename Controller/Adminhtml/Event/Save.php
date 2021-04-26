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

use Magento\Framework\Exception\LocalizedException;






/**
 * Save class is a controller to save events
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\EventFactory
     */
    protected $event;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;
	

	
	protected $dataPersistor;
    
    /**
     * __construct function
     *
     * @param \Magento\Backend\App\Action\Context            $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Stdlib\DateTime\DateTime    $dateTime
     * @param \Webkul\EventManager\Model\EventFactory        $Eventfactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
		\Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Webkul\EventManager\Model\EventFactory $Eventfactory
    ) {
		
		
        $this->event = $Eventfactory;
        $this->cacheTypeList = $cacheTypeList;
        $this->dateTime = $dateTime;
		 $this->dataPersistor = $dataPersistor;
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
     * Save event controller
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $eventObj = $this->event->create();
        $event_data = $this->getRequest()->getParams();
        $data = $event_data['event_data'];
		
$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/saveevent.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);

$logger->debug('data =  ' . print_r($data,1) . '<br>');	
		
		
        try {
            if (isset($data['event_id'])) {

                $eventObj->load($data['event_id']);
				
				
				
				
				
				
				
				 $data = $this->_filterFoodData($data);
				
                $eventObj->setData($data);
                $eventObj->save();
                $this->messageManager->addSuccess(__("Event data Successfully Updated"));
                $this->cleanCache();
            } else {
                $today = $this->dateTime->date('m/d/y');
                if (strtotime($today) <= strtotime($data['event_date'])) {
					
					
					 $data = $this->_filterFoodData($data);
					
					
                    $eventObj->setData($data);
                    $eventObj->save();
					
					
					$lastInsrtedId = $eventObj->getId();
					
					$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
			$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection();
			$tableName = $resource->getTableName('webkul_eventmanager_maptable'); //gives table name with prefix
			//Insert Data into table
			$sql = "Insert Into " . $tableName . " (map_id, group_event_id, event_id) Values ('','1',$lastInsrtedId)";
			$connection->query($sql);

			
					
					
					
                    $this->messageManager->addSuccess(__("Event has been successfully saved"));
                    $this->cleanCache();
                } else {
                    $this->messageManager->addError(__("Event has not been successfully saved."));
                    $this->messageManager->addNotice(
                        __("The event date should be declared for the current date or future date.")
                    );
                    $this->cleanCache();
                }
                
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__("Some error occure please retry"));
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect->setPath('eventmanager/event/eventpage');
    }
	
	
	public function _filterFoodData(array $rawData)
    {
        $data = $rawData;
        if (isset($data['event_homepdf'][0]['name'])) {
            $data['event_homepdf'] = $data['event_homepdf'][0]['name'];
        } else {
            $data['event_homepdf'] = null;
        }
		if (isset($data['event_gympdf'][0]['name'])) {
            $data['event_gympdf'] = $data['event_gympdf'][0]['name'];
        } else {
            $data['event_gympdf'] = null;
        }
		
        return $data;
    }
	
	

    /**
     * CleanCache function is used clean particular cahce types
     */
    public function cleanCache()
    {
        $types = ['layout','block_html','full_page'];
        foreach ($types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
    }
}
