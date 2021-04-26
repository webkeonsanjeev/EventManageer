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
namespace Webkul\EventManager\Block\Adminhtml\GroupEvent;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton is used to provide the data of reset button of event
 */
class ResetButton implements ButtonProviderInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var urlBuilder
     */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->registry = $registry;
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * GetButtonData is used to get attributes of button
     *
     * @return array
     */
    public function getButtonData()
    {
        $eventId = $this->registry->registry('group_event_id');
        $data='';
        if ($eventId) {
            $data = [
                'label' => __('Reset'),
                'class' => 'primary',
                'id' => 'reset-button',
                'on_click' => 'location.href="'.$this->getCurrentUrl().'"'
            ];
        }
        return $data;
    }

    /**
     * GetCurrentUrl is used to get current url
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        $id = $this->registry->registry('group_event_id');
        return $this->urlBuilder->getUrl('*/*/addevent', ['group_event_id' => $id]);
    }
}
