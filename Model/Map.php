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
namespace Webkul\EventManager\Model;

use Webkul\EventManager\Api\Data\MapInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Map class Abstract Model
 */
class Map extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, MapInterface
{
    const CACHE_TAG = 'webkul_eventmanager_map';

    /**
     * @var _cacheTag
     */
    protected $_cacheTag = 'webkul_eventmanager_map';

    /**
     * @var _eventPrefix
     */
    protected $_eventPrefix = 'webkul_eventmanager_map';

    /**
     * _construct
     */
    protected function _construct()
    {
        $this->_init(\Webkul\EventManager\Model\ResourceModel\Map::class);
    }

    /**
     * GetIdentities function get All Ids
     *
     * @return string
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Id function
     *
     * @return int|null
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set Id function
     *
     * @param string $id
     *
     * @return Webkul\EventManager\Api\Data\MapInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

     /**
      * Get group Id function
      *
      * @return int|null
      */
    public function getGroupEventId()
    {
        return parent::getData(self::ENTITY_GROUP_ID);
    }

    /**
     * Set Id function
     *
     * @param string $groupId
     *
     * @return Webkul\EventManager\Api\Data\MapInterface
     */
    public function setGroupEeventId($groupId)
    {
        return $this->setData(self::ENTITY_GROUP_ID, $groupId);
    }
     /**
      * Get event Id function
      *
      * @return int|null
      */
    public function getEventId()
    {
        return parent::getData(self::ENTITY_EVENT_ID);
    }
    
    /**
     * Set Id function
     *
     * @param string $eventId
     *
     * @return Webkul\EventManager\Api\Data\MapInterface
     */
    public function setEventId($eventId)
    {
        return $this->setData(self::ENTITY_EVENT_ID, $eventId);
    }
}
