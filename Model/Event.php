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

use Webkul\EventManager\Api\Data\EventInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Event class Abstract Model
 */
class Event extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, EventInterface
{
    const CACHE_TAG = 'webkul_eventmanager_event';

    /**
     * @var _cacheTag
     */
    protected $_cacheTag = 'webkul_eventmanager_event';

    /**
     * @var eventPrefix
     */
    protected $_eventPrefix = 'webkul_eventmanager_event';

    /**
     * _construct
     */
    protected function _construct()
    {
        $this->_init(\Webkul\EventManager\Model\ResourceModel\Event::class);
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
     * Set id function
     *
     * @param int $id
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Get Event name function
     *
     * @return string
     */
    public function getEventName()
    {
         return parent::getData(self::ENTITY_NAME);
    }

    /**
     * Set event name function
     *
     * @param string $name
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventName($name)
    {
        return parent::setData(self::ENTITY_NAME, $name);
    }

    /**
     * Get event date function
     *
     * @return date
     */
    public function getEventDate()
    {
        return parent::getData(self::ENTITY_DATE);
    }

    /**
     * Set event date function
     *
     * @param string $date
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventDate($date)
    {
         return parent::setData(self::ENTITY_DATE, $date);
    }

    /**
     * Get event content function
     *
     * @return string
     */
    public function getEventContent()
    {
        return parent::getData(self::ENTITY_CONTENT);
    }

    /**
     * Set event content function
     *
     * @param string $content
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventContent($content)
    {
        return parent::setData(self::ENTITY_CONTENT, $content);
    }
	
	
	
	
	
	
	
	
	
	
	
	 /**
     * Get event gympdf function
     *
     * @return string
     */
    public function getEventGympdf()
    {
        return parent::getData(self::ENTITY_GYMPDF);
    }

    /**
     * Set event content function
     *
     * @param string $gympdf
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventGympdf($gympdf)
    {
        return parent::setData(self::ENTITY_GYMPDF, $gympdf);
    }
	
	

	
	
		
	 /**
     * Get event homepdf function
     *
     * @return string
     */
    public function getEventHomepdf()
    {
        return parent::getData(self::ENTITY_HOMEPDF);
    }

    /**
     * Set event content function
     *
     * @param string $homepdf
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventHomepdf($homepdf)
    {
        return parent::setData(self::ENTITY_HOMEPDF, $homepdf);
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

    /**
     * Get event status function
     *
     * @return true|false
     */
    public function getEventStatus()
    {
        return parent::getData(self::ENTITY_STATUS);
    }
    
    /**
     * Set event status function
     *
     * @param string $status
     *
     * @return Webkul\EventManager\Api\Data\EventInterface
     */
    public function setEventStatus($status)
    {
        return parent::setData(self::ENTITY_STATUS, $status);
    }
}
