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
 * Group Event Interface is used to get data related to Group Event
 * @api
 */
interface GroupEventInterface
{
    const ENTITY_ID = 'group_event_id';
    const ENTITY_TITLE = 'group_title';
    const ENTITY_NAME = 'group_name';
    const ENTITY_CODE = 'group_code';
    const ENTITY_SHOW_ON_PAGE = 'show_on_page';
    const ENTITY_STATUS = 'event_status';

    /**
     * GET ID function
     *
     * @return int|null
     */
    public function getId();

    /**
     * SET ID function
     *
     * @param int $id
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setId($id);

    /**
     * GET group title function
     *
     * @return string
     */
    public function getGroupTitle();

    /**
     * Set group event title function
     *
     * @param string $title
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setGroupTitle($title);

    /**
     * Get event name function
     *
     * @return string
     */
    public function getGroupName();

    /**
     * Set Group Event Name function
     *
     * @param string $name
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setGroupName($name);

    /**
     * Get group event code function
     *
     * @return string
     */
    public function getGroupCode();

    /**
     * Set group event code function
     *
     * @param string $code
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setGroupCode($code);

    /**
     * Get show on page function
     *
     * @return string
     */
    public function getShowOnPage();

    /**
     * Set show on page function
     *
     * @param string $pages
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setShowOnPage($pages);

    /**
     * Get group event status function
     *
     * @return true|false
     */
    public function getEventStatus();

    /**
     * Set group event status function
     *
     * @param string $status
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setEventStatus($status);
}
