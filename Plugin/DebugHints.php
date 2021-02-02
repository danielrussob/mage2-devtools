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
    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $_scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;
    /**
     * @var DebugHintsFactory
     */
    protected DebugHintsFactory $_debugHintsFactory;
    /**
     * @var Http
     */
    protected Http $_http;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        DevHelper $devHelper,
        DebugHintsFactory $debugHintsFactory,
        Http $http,
        $debugHintsPath,
        $debugHintsWithParam = null,
        $debugHintsParameter = null
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_debugHintsFactory = $debugHintsFactory;
        $this->_http = $http;
        parent::__construct($scopeConfig, $storeManager, $devHelper, $debugHintsFactory, $http, $debugHintsPath, $debugHintsWithParam, $debugHintsParameter);
    }

    public function afterCreate(
        TemplateEngineFactory $subject,
        TemplateEngineInterface $invocationResult
    ) {
        $storeCode = $this->_storeManager->getStore()->getCode();
        if ($this->_http->getParam('debug', 'no') == 'yes') {
            $showBlockHints = $this->_scopeConfig->getValue(
                self::XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS,
                ScopeInterface::SCOPE_STORE,
                $storeCode
            );
            return $this->_debugHintsFactory->create([
                'subject' => $invocationResult,
                'showBlockHints' => $showBlockHints,
            ]);
        }
        return $invocationResult;
    }
}