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
     * @return void
     */
    public function setMaxpayCheckoutService(\Maxpay\MaxpayModule\Core\MaxpayService $maxpayCheckoutService): void
    {
        $this->maxpayCheckoutService = $maxpayCheckoutService;
    }

    /**
     * Returns Maxpay service.
     *
     * @return \Maxpay\MaxpayModule\Core\MaxpayService
     */
    public function getMaxpayCheckoutService(): \Maxpay\MaxpayModule\Core\MaxpayService
    {
        if ($this->maxpayCheckoutService === null) {
            $this->maxpayCheckoutService = oxNew(\Maxpay\MaxpayModule\Core\MaxpayService::class);
        }

        return $this->maxpayCheckoutService;
    }
}
