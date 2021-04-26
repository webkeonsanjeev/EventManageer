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

namespace Webkul\EventManager\Helper;

/**
 * Webkul EventManager Helper Data.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Initialize dependencies
     *
     * @param \Webkul\EventManager\Model\MapFactory $mapFactory
     */
    public function __construct(
        \Webkul\EventManager\Model\MapFactory $mapFactory
    ) {
        $this->mapFactory = $mapFactory;
    }

    /**
     * @param $eventId string|array
     * CheckEventId function is used to get those group event Ids which are using that event
     *
     * @return array
     */
    public function checkEventId($eventId)
    {
        $groupEventIds = [];
        $collection = $this->mapFactory->create()->getCollection()
        ->addFieldToFilter('event_id', ['eq'=>$eventId]);
        if ($collection->getSize()) {
            foreach ($collection as $coll) {
                $groupEventIds[] = $coll->getGroupEventId();
            }
        }
        return $groupEventIds;
    }
}
