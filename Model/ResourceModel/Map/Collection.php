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
namespace Webkul\EventManager\Model\ResourceModel\Map;

/**
 * Collection class event
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var _idFieldName
     */
    protected $_idFieldName = 'map_id';

    /**
     * @var eventPrefix
     */
    protected $_eventPrefix = 'webkul_eventmanager_map_collection';

    /**
     * @var eventObject
     */
    protected $_eventObject = 'map_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $mapClass = \Webkul\EventManager\Model\ResourceModel\Map::class;
        $this->_init(\Webkul\EventManager\Model\Map::class, $mapClass);
    }
}
