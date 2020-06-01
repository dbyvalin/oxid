<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */
namespace Maxpay\MaxpayModule\Controller;

/**
 * Main Maxpay controller
 */
class FrontendController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * @var \Maxpay\MaxpayModule\Core\Logger
     */
    protected $logger = null;

    /**
     * @var \Maxpay\MaxpayModule\Core\Config
     */
    protected $maxpayConfig = null;

    public function postback()
    {
        echo 'postback processing here';
    }
    
    /**
     * Return Maxpay logger
     *
     * @return \Maxpay\MaxpayModule\Core\Logger
     */
    public function getLogger()
    {
        if (is_null($this->logger)) {
            $session = \OxidEsales\Eshop\Core\Registry::getSession();
            $this->logger = oxNew(\Maxpay\MaxpayModule\Core\Logger::class);
            $this->logger->setLoggerSessionId($session->getId());
        }

        return $this->logger;
    }

    /**
     * Return Maxpay config.
     *
     * @return \Maxpay\MaxpayModule\Core\Config
     */
    public function getMaxpayConfig()
    {
        if (is_null($this->maxpayConfig)) {
            $this->setMaxpayConfig(oxNew(\Maxpay\MaxpayModule\Core\Config::class));
        }

        return $this->maxpayConfig;
    }

    /**
     * Set Maxpay config.
     *
     * @param \Maxpay\MaxpayModule\Core\Config $maxpayConfig config
     */
    public function setMaxpayConfig($maxpayConfig)
    {
        $this->maxpayConfig = $maxpayConfig;
    }

    /**
     * Logs passed value.
     *
     * @param mixed $value
     */
    public function log($value)
    {
        if ($this->getMaxpayConfig()->isLoggingEnabled()) {
            $this->getLogger()->log($value);
        }
    }
}
