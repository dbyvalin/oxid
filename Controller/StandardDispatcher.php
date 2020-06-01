<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */
namespace Maxpay\MaxpayModule\Controller;

/**
 * PayPal Standard Checkout dispatcher class
 */
class StandardDispatcher extends \Maxpay\MaxpayModule\Controller\Dispatcher
{
    /**
     * @return string
     */
    public function setCheckout()
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $session->setVariable("maxpay", "1");
        
        try {
            $basket = $session->getBasket();
            $basket->setPayment("oxidmaxpay");
            $basket->onUpdate();
            $basket->calculateBasket(true);

        } catch (\OxidEsales\Eshop\Core\Exception\StandardException $excp) {
            // return to basket view
            return "basket";
        }
        
        return 'order';
    }
}
