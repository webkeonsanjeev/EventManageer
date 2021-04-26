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

use Webkul\EventManager\Api\Data\GroupEventInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Group Event class Abstract Model
 */
class GroupEvent extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, GroupEventInterface
{
    const CACHE_TAG = 'webkul_eventmanager_groupevent';

    /**
     * @var _cacheTag
     */
    protected $_cacheTag = 'webkul_eventmanager_groupevent';

    /**
     * @var _eventPrefix
     */
    protected $_eventPrefix = 'webkul_eventmanager_groupevent';

    /**
     * _construct function
     */
    protected function _construct()
    {
        $this->_init(\Webkul\EventManager\Model\ResourceModel\GroupEvent::class);
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
     * GET ID function
     *
     * @return int|null
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * SET ID function
     *
     * @param int $id
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * GET group title function
     *
     * @return string
     */
    public function getGroupTitle()
    {
        return parent::getData(self::ENTITY_TITLE);
    }

    /**
     * Set group event title function
     *
     * @param string $title
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setGroupTitle($title)
    {
        return $this->setData(self::ENTITY_TITLE, $title);
    }

    /**
     * Get group event name function
     *
     * @return string
     */
    public function getGroupName()
    {
        return parent::getData(self::ENTITY_NAME);
    }

    /**
     * Set Group Event Name function
     *
     * @param string $name
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setGroupName($name)
    {
        return $this->setData(self::ENTITY_NAME, $name);
    }

    /**
     * Get group event code function
     *
     * @return string
     */
    public function getGroupCode()
    {
        return parent::getData(self::ENTITY_CODE);
    }

    /**
     * Set group event code function
     *
     * @param string $code
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setGroupCode($code)
    {
        return $this->setData(self::ENTITY_CODE, $code);
    }

    /**
     * Get show on page function
     *
     * @return string
     */
    public function getShowOnPage()
    {
        return parent::getData(self::ENTITY_SHOW_ON_PAGE);
    }

    /**
     * Set show on page function
     *
     * @param string $pages
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setShowOnPage($pages)
    {
        return $this->setData(self::ENTITY_SHOW_ON_PAGE, $pages);
    }
    
    /**
     * Get group event status function
     *
     * @return true|false
     */
    public function getEventStatus()
    {
        return parent::getData(self::ENTITY_STATUS);
    }
    
    /**
     * Set group event status function
     *
     * @return Webkul\EventManager\Api\Data\GroupEventInterface
     */
    public function setEventStatus($status)
    {
        return $this->setData(self::ENTITY_STATUS, $status);
    }
}
