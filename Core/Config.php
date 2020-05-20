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
     * Maxpay payment was triggered via standard checkout by selecting PP as the payment method.
     *
     * @var int
     */
    const OEPAYPAL_ECS = 1;

    /**
     * Maxpay payment was triggered by shortcut button in basket step.
     *
     * @var int
     */
    const OEPAYPAL_SHORTCUT = 2;

    /**
     * Name of session variable that marks how payment was triggered.
     *
     * @var string
     */
    const OEPAYPAL_TRIGGER_NAME = 'oepaypal';

    /**
     * Name of partnercode array key in case payment was triggered by shortcut button.
     *
     * @var string
     */
    const PARTNERCODE_SHORTCUT_KEY = 'SHORTCUT';

    /**
     * Maxpay module id.
     *
     * @var string
     */
    protected $payPalId = 'oepaypal';

    /**
     * Maxpay host.
     *
     * @var string
     */
    protected $payPalHost = 'api-3t.paypal.com';

    /**
     * Maxpay sandbox host.
     *
     * @var string
     */
    protected $payPalSandboxHost = 'api-3t.sandbox.paypal.com';

    /**
     * Maxpay sandbox Url where user must be redirected after his session gets Maxpay token.
     *
     * @var string
     */
    protected $payPalSandboxUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    /**
     * Maxpay Url where user must be redirected after his session gets Maxpay token.
     *
     * @var string
     */
    protected $payPalUrl = 'https://www.paypal.com/cgi-bin/webscr';

    /**
     * Maxpay sandbox API url.
     *
     * @var string
     */
    protected $payPalSandboxApiUrl = 'https://api-3t.sandbox.paypal.com/nvp';

    /**
     * Maxpay API url.
     *
     * @var string
     */
    protected $payPalApiUrl = 'https://api-3t.paypal.com/nvp';

    /**
     * Maximum possible delivery costs value.
     *
     * @var double
     */
    protected $maxDeliveryAmount = 30;

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
        return $this->payPalId;
    }

    /**
     * Sets Maxpay host.
     *
     * @param string $payPalHost
     */
    public function setMaxpayHost($payPalHost)
    {
        $this->payPalHost = $payPalHost;
    }

    /**
     * Returns Maxpay host.
     *
     * @return string
     */
    public function getMaxpayHost()
    {
        $host = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sMaxpayHost');
        if ($host) {
            $this->setMaxpayHost($host);
        }

        return $this->payPalHost;
    }

    /**
     * Sets Maxpay sandbox host.
     *
     * @param string $payPalSandboxHost
     */
    public function setMaxpaySandboxHost($payPalSandboxHost)
    {
        $this->payPalSandboxHost = $payPalSandboxHost;
    }

    /**
     * Returns Maxpay sandbox host.
     *
     * @return string
     */
    public function getMaxpaySandboxHost()
    {
        $host = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sMaxpaySandboxHost');
        if ($host) {
            $this->setMaxpaySandboxHost($host);
        }

        return $this->payPalSandboxHost;
    }

    /**
     * Returns Maxpay OR Maxpay sandbox host.
     *
     * @return string
     */
    public function getHost()
    {
        if ($this->isSandboxEnabled()) {
            $url = $this->getMaxpaySandboxHost();
        } else {
            $url = $this->getMaxpayHost();
        }

        return $url;
    }

    /**
     *  Api Url setter
     *
     * @param string $payPalApiUrl
     */
    public function setMaxpayApiUrl($payPalApiUrl)
    {
        $this->payPalApiUrl = $payPalApiUrl;
    }

    /**
     *  Api Url getter
     *
     * @return string
     */
    public function getMaxpayApiUrl()
    {
        $url = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sMaxpayApiUrl');
        if ($url) {
            $this->setMaxpayApiUrl($url);
        }

        return $this->payPalApiUrl;
    }

    /**
     * Maxpay sandbox api url setter
     *
     * @param string $payPalSandboxApiUrl
     */
    public function setMaxpaySandboxApiUrl($payPalSandboxApiUrl)
    {
        $this->payPalSandboxApiUrl = $payPalSandboxApiUrl;
    }

    /**
     * Maxpay sandbox api url getter
     *
     * @return string
     */
    public function getMaxpaySandboxApiUrl()
    {
        $url = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sMaxpaySandboxApiUrl');
        if ($url) {
            $this->setMaxpaySandboxApiUrl($url);
        }

        return $this->payPalSandboxApiUrl;
    }

    /**
     * Returns end point url
     *
     * @return string
     */
    public function getApiUrl()
    {
        if ($this->isSandboxEnabled()) {
            $url = $this->getMaxpaySandboxApiUrl();
        } else {
            $url = $this->getMaxpayApiUrl();
        }

        return $url;
    }

    /**
     * Maxpay Url Setter
     *
     * @param string $payPalUrl
     */
    public function setMaxpayUrl($payPalUrl)
    {
        $this->payPalUrl = $payPalUrl;
    }

    /**
     * Maxpay sandbox url setter
     *
     * @param string $payPalSandboxUrl
     */
    public function setMaxpaySandboxUrl($payPalSandboxUrl)
    {
        $this->payPalSandboxUrl = $payPalSandboxUrl;
    }

    /**
     * Maxpay sandbox url getter
     *
     * @return string
     */
    public function getMaxpayUrl()
    {
        $url = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sMaxpayUrl');
        if ($url) {
            $this->setMaxpayUrl($url);
        }

        return $this->payPalUrl;
    }

    /**
     * Maxpay sandbox url getter
     *
     * @return string
     */
    public function getMaxpaySandboxUrl()
    {
        $url = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sMaxpaySandboxUrl');
        if ($url) {
            $this->setMaxpaySandboxUrl($url);
        }

        return $this->payPalSandboxUrl;
    }

    /**
     * Get Maxpay url.
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->isSandboxEnabled()) {
            $url = $this->getMaxpaySandboxUrl();
        } else {
            $url = $this->getMaxpayUrl();
        }

        return $url;
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
     * Returns true of GiroPay is ON (not implemented yet)
     *
     * @return bool
     */
    public function isGiroPayEnabled()
    {
        return false;
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
     * Returns Maxpay private key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        if ($this->isSandboxEnabled()) {
            return $this->getParameter('sMaxpayTestPrivateKey');
        }

        return $this->getParameter('sMaxpayPrivateKey');
    }

    /**
     * Returns Maxpay public key
     *
     * @return string
     */
    public function getPublicKey()
    {
        if ($this->isSandboxEnabled()) {
            return $this->getParameter('sMaxpayTestPublicKey');
        }

        return $this->getParameter('sMaxpayPublicKey');
    }

    /**
     * Returns redirect url.
     *
     * @param string $token      token to append to redirect url.
     * @param string $userAction checkout button action - continue (standard checkout) or commit (express checkout)
     *
     * @return string
     */
    public function getMaxpayCommunicationUrl($token = null, $userAction = 'continue')
    {
        return $this->getUrl() . '&cmd=_express-checkout&token=' . (string) $token . '&useraction=' . (string) $userAction;
    }


    /**
     * Get logo Url based on selected settings
     * Returns shop url, or false
     *
     * @return string|bool
     */
    public function getLogoUrl()
    {
        $logoUrl = false;

        return $logoUrl;
    }

    /**
     * Returns IPN callback url
     *
     * @return string
     */
    public function getIPNCallbackUrl()
    {
        return $this->getShopUrl() . 'index.php?cl=oepaypalipnhandler&fnc=handleRequest&shp=' . $this->getShopId();
    }

    /**
     * Methods checks if sending of IPN callback url to Maxpay is supressed by configuration.
     *
     * @return bool
     */
    public function suppressIPNCallbackUrl()
    {
        return (bool) \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('OEMaxpayDisableIPN');
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
     * @deprecated in dev-master (2018-04-27); Use OxidEsales\MaxpayModule\Core\IPnConfig::getIPNResponseUrl()
     *
     * Returns Url for IPN response call to notify Maxpay
     *
     * @return string
     */
    public function getIPNResponseUrl()
    {
        return $this->getUrl() . '&cmd=_notify-validate';
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
     * Returns max delivery amount.
     *
     * @return integer
     */
    public function getMaxMaxpayDeliveryAmount()
    {
        $maxDeliveryAmount = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dMaxMaxpayDeliveryAmount');
        if (!$maxDeliveryAmount) {
            $maxDeliveryAmount = $this->maxDeliveryAmount;
        }

        return $maxDeliveryAmount;
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
     * Detects device type
     *
     * @return bool
     */
    public function isDeviceMobile()
    {
        $userAgent = oxNew(\OxidEsales\MaxpayModule\Core\UserAgent::class);

        return ($userAgent->getDeviceType() == 'mobile');
    }

    /**
     * Returns id of shipping assigned for EC for mobile devices
     *
     * @return string
     */
    public function getMobileECDefaultShippingId()
    {
        return \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sOEMaxpayMECDefaultShippingId');
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
     * Was the payment triggered by shortcut button or not?
     *
     * @return bool
     */
    protected function isShortcutPayment()
    {
        $trigger = (int) \OxidEsales\Eshop\Core\Registry::getSession()->getVariable(self::OEPAYPAL_TRIGGER_NAME);
        return (bool) ($trigger == self::OEPAYPAL_SHORTCUT);
    }
}
