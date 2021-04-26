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
namespace Webkul\EventManager\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * GroupEventActions class is to change render data of group events
 */
class GroupEventActions extends Column
{
    /**
    * Url path
    */
    const EVENT_URL_PATH_EDIT = 'eventmanager/GroupEvent/addgroupevent';
    const EVENT_URL_PATH_DELETE = 'eventmanager/GroupEvent/delete';
    
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
 
    /**
     * @var string
     */
    private $editUrl;
 
    /**
     * __construct function
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     * @param string             $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::EVENT_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['group_event_id'])) {
                    $urlEdit = $this->urlBuilder->getUrl($this->editUrl, ['group_event_id' => $item['group_event_id']]);
                    $item[$name]['edit'] = [
                        'href' => $urlEdit,
                        'label' => __('Edit')
                    ];
                   
                    $path=self::EVENT_URL_PATH_DELETE;
                    $urlDelete = $this->urlBuilder->getUrl($path, ['group_event_id' => $item['group_event_id']]);
                    $item[$name]['delete'] = [
                        'href' => $urlDelete,
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.group_name }"'),
                            'message' => __('Are you sure you want to delete a "${ $.$data.group_name }" record?')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
