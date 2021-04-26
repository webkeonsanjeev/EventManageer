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
namespace Webkul\EventManager\Model\GroupEvent;

/**
 * DataProvider class is used to provide data
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;

    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory
     */
    protected $eventCollection;

    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Map\CollectionFactory
     */
    protected $mapCollection;

    /**
     * @var \Webkul\EventManager\Model\EventFactory
     */
    protected $event;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var loadedData
     */
    protected $loadedData;
    
    /**
     * __construct function
     *
     * @param string                                                                $name
     * @param string                                                                $primaryFieldName
     * @param string                                                                $requestFieldName
     * @param \Webkul\EventManager\Model\ResourceModel\GroupEvent\CollectionFactory $groupeventCollectionFactory
     * @param \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory      $eventCollectionFactory
     * @param \Webkul\EventManager\Model\ResourceModel\Map\CollectionFactory        $mapCollectionFactory
     * @param \Webkul\EventManager\Model\EventFactory                               $eventFactory
     * @param \Magento\Cms\Model\ResourceModel\Page\CollectionFactory               $pageCollectionFactory
     * @param \Magento\Framework\Registry                                           $registry
     * @param array                                                                 $meta
     * @param array                                                                 $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Webkul\EventManager\Model\ResourceModel\GroupEvent\CollectionFactory $groupeventCollectionFactory,
        \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory $eventCollectionFactory,
        \Webkul\EventManager\Model\ResourceModel\Map\CollectionFactory $mapCollectionFactory,
        \Webkul\EventManager\Model\EventFactory $eventFactory,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory,
        \Magento\Framework\Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $groupeventCollectionFactory->create();
        $this->eventCollection = $eventCollectionFactory->create();
        $this->pageCollection = $pageCollectionFactory->create();
        $this->mapCollection = $mapCollectionFactory->create();
        $this->event = $eventFactory->create();
        $this->registry = $registry;
    }
    
    /**
     * GetData ,this function is used provide data to ui form element
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
             return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $data) {
            $this->loadedData[$data->getId()]['GroupEvent'] = $data->getData();
            $this->loadedData[$data->getId()]['GroupEvent']['show_on_page'] = explode(",", $data['show_on_page']);
            $this->loadedData[$data->getId()]['GroupEvent']['events'] = $this->getEvents();
        }
        return $this->loadedData;
    }
    
    /**
     * GetMeta provide meta to configure Ui form element
     *
     * @return array metadata
     */
    public function getMeta()
    {
        $this->meta['GroupEvent'] = [
            'arguments' => [
            ],
            'children' => [
                'events' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'component' => 'Magento_Catalog/js/components/new-category',
                                'filterOptions' => true,
                                'chipsEnabled' => true,
                                'disableLabel' => true,
                                'label' => __('Events'),
                                'levelsVisibility' => '1',
                                'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                'options' => $this->getEventTree(),
                                'listens' => [
                                    'index=create_category:responseData' => 'setParsed',
                                    'newOption' => 'toggleOptionSelected'
                                ],
                                'config' => [
                                    'dataScope' => 'events',
                                ],
                                'sortOrder' => 4,
                                'validation' => [
                                      'required-entry' => true
                                ],
                            ]
                        ]
                    ]
                ],
                'show_on_page' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'select',
                                'componentType' => 'field',
                                'component' => 'Magento_Catalog/js/components/new-category',
                                'filterOptions' => true,
                                'chipsEnabled' => true,
                                'disableLabel' => true,
                                'label' => __('Add Pages on show'),
                                'levelsVisibility' => '1',
                                'options' => $this->getPageTree(),
                                'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                'listens' => [
                                    'index=create_category:responseData' => 'setParsed',
                                    'newOption' => 'toggleOptionSelected'
                                ],
                                'config' => [
                                    'dataScope' => 'show_on_page',
                                ],
                                'sortOrder' => 5,
                                'validation' => [
                                      'required-entry' => true
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return  $this->meta;
    }
    
    /**
     * GetEventTree ,provide options to to events elements fields.
     *
     * @return array list data in
     */
    protected function getEventTree()
    {
        $item = $this->eventCollection->getData();
        $data = [];
        foreach ($item as $value) {
            if ($value['event_status']== 1) {
                $data[] = [
                    'label'=> $value['event_name'],
                    'value'=>$value['event_id']
                ];
            }
        }
        return $data;
    }
    
    /**
     * GetEvents function provide events to show in group event edit form page when admin edit the group event.
     *
     * @return array.
     */
    protected function getEvents()
    {
        $data = [];
        $groupId = $this->registry->registry('group_event_id');
        $item = $this->mapCollection->addFieldToFilter('group_event_id', ['eq'=>$groupId])->getData();
        foreach ($item as $key => $value) {
            $eventId = $value['event_id'];
            $data[] = $eventId;
        }
        return $data;
    }
    
    /**
     * GetPageTree function provide options to to pages list for page on show fields element in group edit form.
     *
     * @return array
     */
    public function getPageTree()
    {
        $item = $this->pageCollection->getData();
        $data = [];
        foreach ($item as $value) {
            if ($value['is_active'] == 1) {
                $data[] = [
                    'label'=> $value['title'],
                    'value'=> $value['page_id']
                ];
            }
        }
        return $data;
    }
}
