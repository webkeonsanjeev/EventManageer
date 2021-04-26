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
namespace Webkul\EventManager\Model\Config;

/**
 * Option class is used to provide option
 */
class Option implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory
     */
    protected $eventCollection;
    
    /**
     * __construct function
     *
     * @param \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory $eventCollectionFactory
     */
    public function __construct(
        \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory $eventCollectionFactory
    ) {
        $this->eventCollection = $eventCollectionFactory->create();
    }
    
    /**
     * ToOptionArray function
     *
     * @return array
     */
    public function toOptionArray()
    {
        $item = $this->eventCollection->getData();
        $data = [];
        foreach ($item as $value) {
            if ($value['event_status']== 1) {
                $data[] = [
                    'label'=> $value['event_name'],
                    'is_active' => 1,
                    'value'=>$value['event_id']
                ];
            }
        }
        return $data;
    }
}
