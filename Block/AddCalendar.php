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
namespace Webkul\EventManager\Block;

/**
 * AddCalendar class is used to provide data to calendar
 */
class AddCalendar extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory
     */
    protected $eventCollection;

    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Map\CollectionFactory
     */
    protected $mapCollection;

    /**
     * @var \Webkul\EventManager\Model\ResourceModel\GroupEvent\CollectionFactory
     */
    protected $groupEvent;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $filterProvider;

    /**
     * @var \Magento\Cms\model\Page
     */
    protected $cmsPage;

    /**
     * @var template
     */
    protected $template = "Webkul_EventManager::addcalendar.phtml";
	
	protected $_customerSession; 
    
    /**
     * __construct function
     *
     * @param \Magento\Framework\View\Element\Template\Context                      $context
     * @param \Webkul\EventManager\Model\ResourceModel\Map\CollectionFactory        $mapCollectionFactory
     * @param \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory      $eventCollectionFactory
     * @param \Magento\Cms\Model\Template\FilterProvider                            $filterProvider
     * @param \Magento\Cms\model\Page                                               $page
     * @param \Webkul\EventManager\Model\ResourceModel\GroupEvent\CollectionFactory $groupEventFactory
     * @param \Magento\Framework\Json\Helper\Data                                   $jsonHelper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                           $dateTime
     * @param array                                                                 $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\EventManager\Model\ResourceModel\Map\CollectionFactory $mapCollectionFactory,
        \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory $eventCollectionFactory,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\model\Page $page,
        \Webkul\EventManager\Model\ResourceModel\GroupEvent\CollectionFactory $groupEventFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
		\Magento\Customer\Model\Session $session,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dateTime = $dateTime;
        $this->eventCollection = $eventCollectionFactory;
        $this->mapCollection = $mapCollectionFactory;
        $this->filterProvider = $filterProvider;
        $this->cmsPage = $page;
        $this->groupEvent = $groupEventFactory;
        $this->jsonHelper = $jsonHelper;
		$this->_customerSession = $session;
    }
    
    /**
     * Get groupIds from cms pages
     *
     * @return array (ids)
     */
    public function getGroupEventIds()
    {
        $ids = [];
        $groupIds = explode(",", $this->_data['groupId']);
        $groupEventDetail = $this->groupEvent->create()
            ->addFieldToFilter(
                'group_event_id',
                ['in' => $groupIds]
            )->addFieldToFilter(
                'event_status',
                ['eq' => 1]
            )->getData();
        foreach ($groupEventDetail as $value) {
            $ids[] = $value['group_event_id'];
        }
        return $this->checkValidGroupIdOnStore($ids);
    }

    /**
     * GetAllEventsIds function for getting events ids
     *
     * @return array
     */
    public function getAllEventsIds()
    {
        $eventsIds = [];
         $groupEventIds = $this->getGroupEventIds();
         $groupEventData = $this->mapCollection->create()
             ->addFieldToFilter('group_event_id', ['in'=>$groupEventIds])->getData();
        foreach ($groupEventData as $value) {
            $eventIds[] = $value['event_id'];
        }
         return array_unique($eventIds);
    }

    /**
     * GetgroupEvents function
     *
     * @return array (group name and its id)
     */
    public function getgroupEvents()
    {
        $groupEvents= [];
        $ids = $this->getGroupEventIds();
        $groupEventDetail = $this->groupEvent->create()
            ->addFieldToFilter(
                'group_event_id',
                ['in' => $ids]
            )->addFieldToFilter(
                'event_status',
                ['eq' => 1]
            )
            ->getData();
        foreach ($groupEventDetail as $value) {
            $groupEvents[] = ['group_event_id' => $value['group_event_id'] ,'group_name' => $value['group_name']];
        }
        return $groupEvents;
    }

    /**
     * GetEventsData function, send all events data to js file to show event
     *
     * @param  string $id
     * @return string , in json type.
     */
    public function getEventsData($id = null)
    {
        $data = $this->getEventsArray($id);
        $data = $this->makeMultiEventPossible($data);
        $data = $this->getFormatedData($data);
        return $data;
    }

    /**
     * GetEventData function, send data to js file to show event
     *
     * @param  string $id
     * @return string , in json type.
     */
    public function getEventData($eventId = null)
    {
        if (!$eventId) {
            return $this->dateTime->date('Y-m-d');
        }
        $date = '';
        $data = $this->eventCollection->create()
            ->addFieldToFilter('event_id', ['eq'=>$eventId])
            ->addFieldToFilter('event_status', ['eq' => 1])->getData();
        foreach ($data as $value) {
            $date = $value['event_date'];
        }
        $date = explode('-', $date);
        return $date;
    }

    /**
     * GetEventsArray function
     *
     * @param  int $id
     * @return array
     */
    public function getEventsArray($id = null)
    {
        $data = [];
        $groupId = $this->getGroupEventIds();
        if ($id) {
            $groupId = [];
            $groupId[] = $id;
        }
        $groupId = $this->groupEventId();
        $mapData = $this->mapCollection->create()->addFieldToFilter('group_event_id', ['in'=>$groupId])->getData();
        $eventIds = [];
        foreach ($mapData as $value) {
                $eventIds[] = $value['event_id'];
        }
        $eventIds = array_unique($eventIds);
        $collection = $this->eventCollection
            ->create()
            ->addFieldToFilter('event_id', ['in'=>$eventIds])
            ->addFieldToFilter('event_status', ['eq' => 1])
            ->getData();
        if ($this->getConfigEventViewType() == 0) {
            $date = $this->dateTime->date("Y-m-d");
            foreach ($collection as $value) {
                $value['event_date'] = $this->dateTime->date("Y-m-d", strtotime($value['event_date']));
                if ($date <= $value['event_date']) {
                    $data[] = $value;
                }
            }
            return $data;
        } else {
            return $collection;
        }
    }

    /**
     * GetFormatedData function make array data in required formate before sending to js via template
     *
     * @param  array $data
     * @return array
     */
    public function getFormatedData($data)
    {
        $arr = [];
        foreach ($data as $value) {
            $height = $this->getConfigEventCalendarHeight()+13;
            $divOpen = "<div id = 'wk_calendar_content' style='height:".$height."px;' >";
            $divClose = $this->filterProvider->getPageFilter()->filter($value['event_content'])."</div>";
            $arr[$this->dateTime->date("m-d-Y", strtotime($value['event_date']))] = $divOpen.$divClose;
        }
        return $arr;
    }

    /**
     * GroupEventId function
     *
     * @return integer
     */
    public function groupEventId()
    {
        return $this->_request->getParam('id');
    }

    /**
     * EventId function
     *
     * @return integer
     */
    public function eventId()
    {
        return $this->_request->getParam('eventId');
    }

    /**
     * IsHomePage function, cheching for home page is or not
     *
     * @return boolean
     */
    public function isHomePage()
    {
        if ($this->_request->getFullActionName() == 'cms_index_index') {
            return true;
        }
        return false;
    }

    /**
     * IsHomePage function, cheching for home page is or not
     *
     * @return boolean
     */
    public function getCmsPageUrl()
    {
        if (!$this->isHomePage()) {
             return $this->getUrl($this->cmsPage->getIdentifier());
        }
    }

    /**
     * GetConfiSetting function, this function provide the configuration value.
     *
     * @return int
     */
    public function getConfiSetting()
    {
        $configPath = 'GroupEvent/GroupEventSetting/status';
        return $this->_scopeConfig->getValue($configPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * GetStoreId function, get current store Id.
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }

    /**
     * Used to check group events are valid to show events are or not.
     *
     * @param  array $groupEventIds
     * @return array
     */
    public function checkValidGroupIdOnStore($groupEventIds)
    {
        $validGroupId = [];
        $groupEventDetail = $this->groupEvent->create()
            ->addFieldToFilter(
                'group_event_id',
                ['in' => $groupEventIds]
            )->getData();
        $StoreId = $this->getStoreId();
        foreach ($groupEventDetail as $value) {
            $storeIds = explode(",", $value['store_id']);
            foreach ($storeIds as $id) {
                if ($id == $StoreId || $id == 0) {
                    $validGroupId[] = $value['group_event_id'];
                }
            }
        }
        return $validGroupId;
    }

    /**
     * ReturnEnableGroupEvents function
     *
     * @param  array $passIds
     * @return array
     */
    public function returnEnableGroupEvents($passIds)
    {
        $ids = [];
        $groupEventDetail = $this->groupEvent->create()
            ->addFieldToFilter(
                'group_event_id',
                ['in' => $passIds]
            )->addFieldToFilter(
                'event_status',
                ['eq' => 1]
            );
        foreach ($groupEventDetail as $value) {
            $ids[] = $value->getGroupEventId();

        }
        return $ids;
    }

    /**
     * MakeMultiEventPossible function
     *
     * @param  array $data
     * @return array
     */
    public function makeMultiEventPossible($data)
    {
		
		$pdfbaselink='pub/media/event/tmp/pdf/';
		
        $multiEventData = [];
        $date = $this->dateTime->date("Y-m-d");
        foreach ($data as $value) {
			
			 $homepadf="";
            if ($value['event_homepdf']!="") {
                $homepadf = "<div style='float:right'><a class='wk-event-link' target='_blank' href='".$pdfbaselink.
                $value['event_homepdf']."'>".__('Download Home PDF')."</a> | </div>";
            }
			
			 $gympadf="";
            if ($value['event_gympdf']!="") {
                $gympadf = "<div style='float:right'><a class='wk-event-link' target='_blank' href='".$pdfbaselink.
                $value['event_gympdf']."'>".__('Download GYM PDF')."</a></div>";
            }
			
			

            $linkAdd="";
            if ($value['event_link']!="") {
                $linkAdd = "<div style='float:right'><a class='wk-event-link' target='_blank' href='".
                $value['event_link']."'>".__('View Details')."</a></div>";
            }
            $check = 0;
            $value['event_date'] = $this->dateTime->date("Y-m-d", strtotime($value['event_date']));
            $eventHtml = $this->getEventContentDiv($value['event_name'],$homepadf,$gympadf, $linkAdd, $value['event_content']);
            if ($this->getConfigEventViewType() == 0) {
                if ($date <= $value['event_date']) {
                    foreach ($multiEventData as &$singleData) {
                        if ($singleData['event_date'] == $value['event_date']) {
                            $singleData['event_content'] = $singleData['event_content']."</br>".$eventHtml;
                            $check = 1;
                        }
                    }
                    if ($check == 0) {
                        $multiEventData[] = [
                                                'event_date' => $value['event_date'],
                                                'event_content' => $eventHtml
                                            ];
                    }
                }
            } else {
                foreach ($multiEventData as &$singleData) {
                    if ($singleData['event_date'] == $value['event_date']) {
                        $singleData['event_content'] = $singleData['event_content']."</br>".$eventHtml;
                        $check = 1;
                    }
                }
                if ($check == 0) {

                    $multiEventData[] = [
                                            'event_date' => $value['event_date'],
                                            'event_content' => $eventHtml
                                        ];
                }
            }
        }
        return $multiEventData;
    }

    /**
     * AsHtml function is used to render html data
     *
     * @return int
     */
    public function asHtml($data)
    {
        return $this->_escaper->escapeHtml($data);
    }

    /**
     * GetConfigEventViewType function int value for future event or all event
     *
     * @return int
     */
    public function getConfigEventViewType()
    {
        $scopePath = 'GroupEvent/GroupEventSetting/event_view_type';
        return $this->_scopeConfig->getValue($scopePath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * GetConfigEventCalendarWidth function for getting width for calendar
     *
     * @return int
     */
    public function getConfigEventCalendarWidth()
    {
        $scopePath = 'GroupEvent/GroupEventSetting/event_calendar_width';
        $width = $this->_scopeConfig->getValue($scopePath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($width>1300) {
            $width = 1300;
        }
        return $width;
    }

    /**
     * GetConfigEventCalendarHeight function for getting height for calendar
     *
     * @return int
     */
    public function getConfigEventCalendarHeight()
    {
        $scopePath = 'GroupEvent/GroupEventSetting/event_calendar_height';
        $height = $this->_scopeConfig->getValue($scopePath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($height>=400) {
            $height = 400;
        }
        return $height;
    }

    /**
     * GetTextColorCode function for getting text color for calendar
     *
     * @return string
     */
    public function getTextColorCode()
    {
        $scopePath = 'GroupEvent/GroupEventSetting/event_calendar_text_color';
        return $this->_scopeConfig->getValue($scopePath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * GetColorCode function for getting background color for calendar
     *
     * @return string
     */
    public function getColorCode()
    {
        $scopePath = 'GroupEvent/GroupEventSetting/event_calendar_color';
        return $this->_scopeConfig->getValue($scopePath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * GetEventContentDiv function for getting html realted content
     *
     * @return string
     */
    public function getEventContentDiv($eventName,$homepadf, $gympadf, $linkAdd, $eventContent)
    {
		
        $divOpen = "<div class='event_content'><div class='wk-eventTitle'>";
        $divClose = $homepadf.$gympadf.$linkAdd."</div>".$eventContent."</div>";
        return $divOpen."<strong>Workout Name : ".$this->asHtml(ucfirst($eventName))."</strong>".$divClose;
    }

    /**
     * Encode the mixed $valueToEncode into the JSON format
     *
     * @param mixed $valueToEncode
     * @return string
     */
    public function jsonEncode($valueToEncode)
    {
        return $this->jsonHelper->jsonEncode($valueToEncode);
    }

    /**
     * Decodes the given $encodedValue string which is
     * encoded in the JSON format
     *
     * @param string $encodedValue
     * @return mixed
     */
    public function jsonDecode($encodedValue)
    {
        return $this->jsonHelper->jsonDecode($encodedValue);
    }
}
