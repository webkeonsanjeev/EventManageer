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
 * VisibilitySource class is used to provide option
 */
class VisibilitySource implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Webkul\EventManager\Model\ResourceModel\Event\CollectionFactory
     */
    protected $eventCollection;
    
    /**
     * ToOptionArray function ,provide selection data in configration system for webkul_EeventManager
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
                    ['value' => '1', 'label' => __('All Events')],
                    ['value' => '0', 'label' => __('Only Future Events')]
                ];
        return $data;
    }
}
