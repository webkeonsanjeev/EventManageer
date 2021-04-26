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
namespace Webkul\EventManager\Block\Adminhtml\Event;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton is used to provide the data of delete button of event
 */
class DeleteButton implements ButtonProviderInterface
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
        $eventId = $this->registry->registry('eventId');
        $data='';
        if ($eventId) {
            $data = [
                'label' => __('Delete'),
                'class' => 'primary delete',
                'id' => 'delete-button',
                'on_click' => 'location.href="'.$this->getDeleteUrl().'"'
            ];
        }
        return $data;
    }

    /**
     * GetDeleteUrl is used to get delete url
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        $eventId = $this->registry->registry('eventId');
        return $this->urlBuilder->getUrl('*/*/delete', ['event_id' => $eventId]);
    }
}
