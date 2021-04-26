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
namespace Webkul\EventManager\Api\Data;

/**
 * Map Interface to get data related to mapping between events and group events
 * @api
 */
interface MapInterface
{
    const ENTITY_ID = 'map_id';
    const ENTITY_GROUP_ID = 'group_event_id';
    const ENTITY_EVENT_ID = 'event_id';
    
    /**
     * Get Id function
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set Id function
     *
     * @param int $id
     *
     * @return Webkul\EventManager\Api\Data\MapInterface
     */
    public function setId($id);

    /**
     * Get group Id function
     *
     * @return int|null
     */
    public function getGroupEventId();
    
    /**
     * Set Id function
     *
     * @param int $groupId
     *
     * @return Webkul\EventManager\Api\Data\MapInterface
     */
    public function setGroupEeventId($groupId);
    
    /**
     * Get event Id function
     *
     * @return int|null
     */
    public function getEventId();
    
    /**
     * Set Id function
     *
     * @param int $eventId
     *
     * @return Webkul\EventManager\Api\Data\MapInterface
     */
    public function setEventId($eventId);
}
