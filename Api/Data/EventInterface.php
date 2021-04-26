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
 * Event Interface is used to get data related to Event
 * @api
 */
interface EventInterface
{
    const ENTITY_ID = 'event_id';
    const ENTITY_NAME = 'event_name';
    const ENTITY_DATE = 'event_date';
    const ENTITY_CONTENT = 'event_content';
	const ENTITY_GYMPDF = 'event_gympdf';
	const ENTITY_HOMEPDF = 'event_homepdf';
    const ENTITY_STATUS = 'event_status';

    /**
     * Get Id function
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set id function
     *
     * @param int $id
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setId($id);

    /**
     * Get Event name function
     *
     * @return string
     */
    public function getEventName();

    /**
     * Set event name function
     *
     * @param string $name
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventName($name);

    /**
     * Get event date function
     *
     * @return date
     */
    public function getEventDate();

    /**
     * Set event date function
     *
     * @param date $date
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventDate($date);

    /**
     * Get event content function
     *
     * @return string
     */
    public function getEventContent();

    /**
     * Set event content function
     *
     * @param string $content
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventContent($content);
	
	
	
	
	
	
	
	
	 /**
     * Get event homepdf function
     *
     * @return string
     */
    public function getEventHomepdf();

    /**
     * Set event homepdf function
     *
     * @param string $homepdf
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventHomepdf($homepdf);
	
	
	
	
	
	
	
	 /**
     * Get event gympdf function
     *
     * @return string
     */
    public function getEventGympdf();

    /**
     * Set event gympdf function
     *
     * @param string $gympdf
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventGympdf($gympdf);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

    /**
     * Get event status function
     *
     * @return true|false
     */
    public function getEventStatus();
    
    /**
     * Set event status function
     *
     * @param int $status
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventStatus($status);
}
