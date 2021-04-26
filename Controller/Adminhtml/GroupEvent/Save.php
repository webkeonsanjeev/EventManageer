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
namespace Webkul\EventManager\Controller\Adminhtml\GroupEvent;

use Magento\Framework\Controller\ResultFactory;

/**
 * Save class is a controller to save events
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\GroupEventFactory
     */
    protected $groupEvent;

    /**
     * @var \Webkul\EventManager\Model\MapFactory
     */
    protected $map;
    
    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageModel;
    
    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $cacheFrontendPool;
    
    /**
     * __construct function
     *
     * @param \Magento\Backend\App\Action\Context          $context
     * @param \Webkul\EventManager\Model\GroupEventFactory $groupEventFactory
     * @param \Webkul\EventManager\Model\MapFactory        $mapFactory
     * @param \Magento\Framework\App\Cache\Frontend\Pool   $cacheFrontendPool
     * @param \Magento\Cms\Model\PageFactory               $pageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Webkul\EventManager\Model\GroupEventFactory $groupEventFactory,
        \Webkul\EventManager\Model\MapFactory $mapFactory,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Cms\Model\PageFactory $pageFactory
    ) {
        $this->groupEvent = $groupEventFactory;
        $this->map = $mapFactory;
        $this->pageModel = $pageFactory;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }

    /**
     * _isAllowed function for valiadte menu
     *
     * @return void
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_EventManager::EventManager');
    }

    /**
     * Execute , in this function we code for save and update the group event Data
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $groupEvent = $this->groupEvent->create();
        $event_data = $this->getRequest()->getParams();
        if ($event_data) {
            $data = $event_data['GroupEvent'];
            $data['store_id'] = $this->checkStoreId($data['store_id']);
            $data['show_on_page'] = $this->filterPageData($data["show_on_page"]);
            $check=1;
            try {
                if (isset($data['group_event_id'])) {
                    $this->save($groupEvent, $data, $data['group_event_id']);
                    $check = $this->saveMap($data);
                    if ($check == 1) {
                        $this->messageManager->addSuccess(__("Group Event data Successfully Upadated"));
                    }
                } else {
                    $id = $this->save($groupEvent, $data);
                    $check = $this->saveMap($data, $id);
                    if ($check == 1) {
                        $this->messageManager->addSuccess(__("Group Event data Successfully Save"));
                    }
                }
            } catch (\Exception $e) {
                $this->messageManager->addError(__("Something went wrong"));
            }
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->clean();
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect->setPath('eventmanager/groupevent/groupeventpage');
        ;
    }

    /**
     * Save function for saving data in group event table
     *
     * @param  Collection $modelObj
     * @param  array      $data
     * @param  string     $id
     * @return string
     */
    public function save($modelObj, $data, $id = null)
    {
        $pages = $data['show_on_page'];
        if ($id!=null) {
            $modelObj->load($id);
            $previousPages = $modelObj->getShowOnPage();
            $this->removeBlock($pages, $id, $previousPages);
        }
        $data['show_on_page'] = implode(",", $data['show_on_page']);
        $modelObj->setData($data);
        $model = $modelObj->save();
        $groupEventId = $model->getId();
        if ($id == null) {
            $this->cmsPages($pages, $groupEventId);
        }
        return  $groupEventId;
    }

    /**
     * SaveMap function for map the group event Id with the events Id
     *
     * @param  array  $data
     * @param  string $id
     * @return string
     */
    public function saveMap($data, $id = null)
    {
        $check=0;
        if ($id == null) {
            $id = $data['group_event_id'];
        }
        if (isset($data['group_event_id'])) {
            if ($data['group_event_id']!=null) {
                $collection = $this->map->create()->getCollection();
                $collection->addFieldToFilter('group_event_id', ['eq'=>$data['group_event_id']]);
                $collection->walk('delete');
            }
        }
        foreach ($data['events'] as $eventId) {
            $this->map->create()->setData(['group_event_id' => $id ,'event_id' => $eventId])->save();
            $check=1;
        }
        return $check;
    }

    /**
     * CmsPages function for set the groupEventId on the cms pages
     *
     * @param  array  $pages
     * @param  string $groupEventId
     * @return void
     */
    public function cmsPages($pages, $groupEventId)
    {
        $collection = $this->pageModel->create()->getCollection()->addFieldToFilter('page_id', ['in' => $pages]);
        foreach ($collection as $data) {
            $content = $data->getContent();
            $match = [];
            if (preg_match("/{{block\sclass.{2}Webkul.EventManager(.*?)}}/", $content, $output_array)) {
                preg_match_all('/groupId="(.*?)"/', $content, $match);
                if (count($match)) {
                    $ids = explode(',', $match['1']['0']);
                    $check = 0;
                    foreach ($ids as $idValue) {
                        if ($idValue == $groupEventId) {
                            $check = 1;
                        }
                    }
                    if ($check == 0) {
                        $ids[] = $groupEventId;
                        $replaceData = 'groupId='.'"'.implode(",", $ids).'"';
                        $alterData = preg_replace('/groupId="(.*?)"/', $replaceData, $content);
                        $data->setData('content', $alterData);
                        $data->save();
                    }
                }
            } else {
                $blockClass='{{block class="Webkul\EventManager\Block\AddCalendar" template=';
                $string = $blockClass.'"Webkul_EventManager::addcalendar.phtml" groupId="'.$groupEventId.'"}}'.$content;
                $data->setData('content', $string);
                $data->save();
            }
        }
    }

    /**
     * RemoveBlock function for remove the group Id freom the cms pages on update
     *
     * @param  array  $currentPages
     * @param  string $id
     * @param  array  $previousPages
     * @return void
     */
    public function removeBlock($currentPages, $id, $previousPages)
    {
        $previousPages = explode(",", $previousPages);
        $result1 = array_diff($currentPages, $previousPages);
        $replace = array_diff($previousPages, $currentPages);
        $result2 = array_intersect($previousPages, $currentPages);
        $arr = $this->concateArray($result1, $result2);
        if (count($arr)) {
            $this->cmsPages($arr, $id);
        }
        if (count($replace)) {
            $collection = $this->pageModel->create()->getCollection()->addFieldToFilter('page_id', ['in' => $replace]);
            foreach ($collection as $data) {
                $content = $data->getContent();
                if (!preg_match("/{{block\sclass.{2}Webkul.EventManager(.*?)}}/", $content)) {
                    continue;
                }
                preg_match_all('/groupId="(.*?)"/', $content, $match);
                if ($match) {
                    $ids = $match['1']['0'];
                    $ids = explode(",", $ids);
                    $remainIds = [];
                    foreach ($ids as $idValue) {
                        if ($idValue != $id) {
                            $remainIds[] = $idValue;
                        }
                    }
                    $replaceData = 'groupId='.'"'.implode(",", $remainIds).'"';
                    $alterData = preg_replace('/groupId="(.*)(")/', $replaceData, $content);
                    $data->setData('content', $alterData);
                    $data->save();
                }
            }
        }
    }

    /**
     * ConcateArray function for cancat the to array
     *
     * @param  array $arr1
     * @param  array $arr2
     * @return array
     */
    public function concateArray($arr1, $arr2)
    {
        $arr = [];
        foreach ($arr1 as $value) {
            $arr[] = $value;
        }
        foreach ($arr2 as $value) {
            $arr[]= $value;
        }
        return $arr;
    }

    /**
     * FilterPageData function for filtering indexes that have null value
     *
     * @param  array $pageData
     * @return array
     */
    public function filterPageData($pageData)
    {
        $arr = [];
        foreach ($pageData as $value) {
            if ($value != null) {
                $arr [] = $value;
            }
        }
        return $arr;
    }
    
    /**
     * To check store_id = 0 that represent global view so hetre need to do this
     *
     * @param  array $data
     * @return string
     */
    public function checkStoreId($data)
    {
        foreach ($data as $value) {
            if ($value == 0) {
                return 0;
            } else {
                return implode(",", $data);
            }
        }
    }
}
