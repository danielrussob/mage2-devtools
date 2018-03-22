<?php

namespace DNAFactory\Devtools\Plugin;

use Magento\Developer\Helper\Data as DevHelper;
use Magento\Developer\Model\TemplateEngine\Decorator\DebugHintsFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\TemplateEngineFactory;
use Magento\Framework\View\TemplateEngineInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Request\Http;

/**
 * Class DebugHints
 * Override of: Magento\Developer\Model\TemplateEngine\Plugin\DebugHints
 * @package DNAFactory\Devtools\Plugin
 */
class DebugHints extends \Magento\Developer\Model\TemplateEngine\Plugin\DebugHints
{

    protected $_httpRequest;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        DevHelper $devHelper,
        DebugHintsFactory $debugHintsFactory,
        $debugHintsPath,
        Http $httpRequest
    ) {
        $this->_httpRequest = $httpRequest;
        parent::__construct($scopeConfig, $storeManager, $devHelper, $debugHintsFactory, $debugHintsPath);
    }

    public function afterCreate(
        TemplateEngineFactory $subject,
        TemplateEngineInterface $invocationResult
    ) {
        $storeCode = $this->storeManager->getStore()->getCode();
        if ($this->_httpRequest->getParam('debug', 'no') == 'yes') {
            $showBlockHints = $this->scopeConfig->getValue(
                self::XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            return $this->debugHintsFactory->create([
                'subject' => $invocationResult,
                'showBlockHints' => $showBlockHints,
            ]);
        }
        return $invocationResult;
    }
}