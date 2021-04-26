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

use \Magento\Backend\App\Action\Context;

/**
 * MassStatus class for performing mass action in admin event manager GroupEvent page grid
 */
class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * @var \Webkul\EventManager\Model\GroupEventFactory
     */
    protected $groupEvent;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;
    
    /**
     * __construct function
     *
     * @param \Magento\Backend\App\Action\Context          $context
     * @param \Magento\Ui\Component\MassAction\Filter      $filter
     * @param \Webkul\EventManager\Model\GroupEventFactory $groupEventFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Webkul\EventManager\Model\GroupEventFactory $groupEventFactory
    ) {
         $this->groupEvent = $groupEventFactory;
            $this->filter = $filter;
         parent::__construct($context);
    }

    /**
     * Mass action for change status
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
          $check = 0;
          $groupEvent = $this->groupEvent->create();
          $collection = $this->filter->getCollection($groupEvent->getCollection());
          $productIds = $collection->getAllIds();
          $data = $this->getRequest()->getParams();
        if (isset($productIds) && isset($data['status'])) {
            $status = $data['status'];
            foreach ($productIds as $value) {
                 $model = $groupEvent->load($value);
                 $model->setData('event_status', $status)->save();
                 $check=1;
            }
        }
        if ($check==1) {
             $this->messageManager->addSuccess(__("Successfully Status Changed"));
        } else {
                $this->messageManager->addError(__("Status Not Changed"));
        }
          $resultRedirect = $this->resultRedirectFactory->create();
             return $resultRedirect->setPath('eventmanager/groupevent/groupeventpage');
    }
}
