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
namespace Webkul\EventManager\Model\Event;

/**
 * DataProvider class is used to provide data
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory
     */
    protected $collection;

    /**
     * @var loadedData
     */
    protected $loadedData;
    
    /**
     * __construct function
     *
     * @param string                                                           $name
     * @param string                                                           $primaryFieldName
     * @param string                                                           $requestFieldName
     * @param \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory $eventCollectionFactory
     * @param \Magento\Framework\Registry                                      $registry
     * @param array                                                            $meta
     * @param array                                                            $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory $eventCollectionFactory,
        \Magento\Framework\Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $eventCollectionFactory->create();
        $this->registry = $registry;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        
        $items = $this->collection->getItems();
        /**
        * @var Customer $event
        */
        foreach ($items as $event) {
            $result['event_data'] = $event->getData();
            $this->loadedData[$event->getId()] = $result;
        }
        return $this->loadedData;
    }
}
