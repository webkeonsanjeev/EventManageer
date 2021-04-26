<?php 

namespace Webkul\EventManager\Controller\Adminhtml\Event;

use Magento\Framework\Controller\ResultFactory;

class Gymupload extends \Magento\Backend\App\Action
{
    public $imageUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Webkul\EventManager\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_EventManager::Event');
    }

    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('event_data[event_gympdf]'); 

//$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/gymupload.log');
//$logger = new \Zend\Log\Logger();
//$logger->addWriter($writer);

//$logger->debug('result =  ' . print_r($result,1) . '<br>');		
						
            $result['gymcookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
?>