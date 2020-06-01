<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */
namespace Maxpay\MaxpayModule\Controller;

/**
 * Abstract Maxpay Dispatcher class
 */
abstract class Dispatcher extends \Maxpay\MaxpayModule\Controller\FrontendController
{
    /**
     * Maxpay checkout service
     *
     * @var \Maxpay\MaxpayModule\Core\MaxpayService
     */
    protected $maxpayCheckoutService;

    /**
     * Default user action for checkout process
     *
     * @var string
     */
    protected $userAction = "continue";

    /**
     * Sets Maxpay checkout service.
     *
     * @param \Maxpay\MaxpayModule\Core\MaxpayService $maxpayCheckoutService
     */
    public function setMaxpayCheckoutService($maxpayCheckoutService)
    {
        $this->maxpayCheckoutService = $maxpayCheckoutService;
    }

    /**
     * Returns Maxpay service
     *
     * @return \Maxpay\MaxpayModule\Core\MaxpayService
     */
    public function getMaxpayCheckoutService()
    {
        if ($this->maxpayCheckoutService === null) {
            $this->maxpayCheckoutService = oxNew(\Maxpay\MaxpayModule\Core\MaxpayService::class);
        }

        return $this->maxpayCheckoutService;
    }

    /**
     * Returns base url, which is used to construct Callback, Return and Cancel Urls
     *
     * @return string
     */
    protected function getBaseUrl()
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $url = \OxidEsales\Eshop\Core\Registry::getConfig()->getSslShopUrl() . "index.php?lang=" . \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage() . "&sid=" . $session->getId() . "&rtoken=" . $session->getRemoteAccessToken();
        $url .= "&shp=" . \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();

        return $url;
    }

    /**
     * Returns Maxpay order object
     *
     * @return \OxidEsales\Eshop\Application\Model\Order|null
     */
    protected function getMaxpayOrder()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        if ($order->loadMaxpayOrder()) {
            return $order;
        }
    }

    /**
     * Returns Maxpay payment object
     *
     * @return \OxidEsales\Eshop\Application\Model\Payment|null
     */
    protected function getMaxpayPayment()
    {
        $userPayment = null;

        if (($order = $this->getMaxpayOrder())) {
            $userPayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
            $userPayment->load($order->oxorder__oxpaymentid->value);
        }

        return $userPayment;
    }
}
