<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Maxpay config class
 */
class Config
{

    /**
     * Maxpay module id.
     *
     * @var string
     */
    protected $maxpayId = 'maxpay';

    /**
     * Maxpay host.
     *
     * @var string
     */
    protected $maxpayHost = 'https://hpp.maxpay.com/hpp';

    /**
     * Please do not change this place.
     * It is important to guarantee the future development of this OXID eShop extension and to keep it free of charge.
     * Thanks!
     *
     * @var array Partner codes based on edition
     */
    protected $partnerCodes = array(
        'EE' => 'OXID_Cart_EnterpriseECS',
        'PE' => 'OXID_Cart_ProfessionalECS',
        'CE' => 'OXID_Cart_CommunityECS',
        'SHORTCUT' => 'Oxid_Cart_ECS_Shortcut'
    );

    /**
     * Return Maxpay module id.
     *
     * @return string
     */
    public function getModuleId()
    {
        return $this->maxpayId;
    }

    /**
     * Sets Maxpay host.
     *
     * @param string $maxpayHost
     */
    public function setMaxpayHost($maxpayHost)
    {
        $this->maxpayHost = $maxpayHost;
    }

    /**
     * Returns Maxpay host.
     *
     * @return string
     */
    public function getMaxpayHost()
    {
        return $this->maxpayHost;
    }

    /**
     * Returns true if logging request/response to Maxpay is enabled
     *
     * @return bool
     */
    public function isLoggingEnabled()
    {
        return $this->getParameter('blMaxpayLoggerEnabled');
    }

    /**
     * Returns true of sandbox mode is ON
     *
     * @return bool
     */
    public function isSandboxEnabled()
    {
        return $this->getParameter('blMaxpaySandboxMode');
    }

    /**
     * Returns SSL or non SSL shop URL without index.php depending on Mall
     * affecting environment is admin mode and current ssl usage status
     *
     * @param bool $admin if admin
     *
     * @return string
     */
    public function getShopUrl($admin = null)
    {
        return \OxidEsales\Eshop\Core\Registry::getConfig()->getCurrentShopUrl($admin);
    }

    /**
     * Wrapper to get language object from registry.
     *
     * @return \OxidEsales\Eshop\Core\Language
     */
    public function getLang()
    {
        return \OxidEsales\Eshop\Core\Registry::getLang();
    }

    /**
     * Wrapper to get utils object from registry.
     *
     * @return \OxidEsales\Eshop\Core\Utils
     */
    public function getUtils()
    {
        return \OxidEsales\Eshop\Core\Registry::getUtils();
    }

    /**
     * Returns shop charset
     *
     * @return string
     */
    public function getCharset()
    {
        $charset = 'UTF-8';

        return $charset;
    }

    /**
     * Returns current URL
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return \OxidEsales\Eshop\Core\Registry::getUtilsUrl()->getCurrentUrl();
    }

    /**
     * Please do not change this place.
     * It is important to guarantee the future development of this OXID eShop extension and to keep it free of charge.
     * Thanks!
     *
     * @return string partner code.
     */
    public function getPartnerCode()
    {
        $facts = new \OxidEsales\Facts\Facts();
        $key = $this->isShortcutPayment() ? self::PARTNERCODE_SHORTCUT_KEY : $facts->getEdition();

        return $this->partnerCodes[$key];
    }

    /**
     * Returns active shop id
     *
     * @return string
     */
    protected function getShopId()
    {
        return \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();
    }

    /**
     * Returns oxConfig instance
     *
     * @return \OxidEsales\Eshop\Core\Config
     */
    protected function getConfig()
    {
        return \OxidEsales\Eshop\Core\Registry::getConfig();
    }

    /**
     * Retrieve apropriate publick key.
     * @return string
     */
    public function getPublicKey(): string
    {
        if ($this->isSandboxEnabled()) {
            $publicKey = $this->getParameter('sMaxpayTestPublicKey');
        } else {
            $publicKey = $this->getParameter('sMaxpayPublickKey');
        }

        return $publicKey;
    }
    
    /**
     * Retrieve apropriate private key.
     * @return string
     */
    public function getPrivateKey(): string
    {
        if ($this->isSandboxEnabled()) {
            $privateKey = $this->getParameter('sMaxpayTestPrivateKey');
        } else {
            $privateKey = $this->getParameter('sMaxpayPrivateKey');
        }

        return $privateKey;
    }
    
    /**
     * Returns module config parameter value
     *
     * @param string $paramName parameter name
     *
     * @return mixed
     */
    public function getParameter($paramName)
    {
        return \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam($paramName);
    }
}
