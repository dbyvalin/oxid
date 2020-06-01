<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Controller;

/**
 * Maxpay Wrapping class
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\WrappingController
 */
class WrappingController extends WrappingController_parent
{
    /**
     * Checks if payment action is processed by Maxpay
     *
     * @return bool
     */
    public function isMaxpay()
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        return ($session->getVariable("paymentid") == "oxidmaxpay") ? true : false;
    }

    /**
     * Detects is current payment must be processed by Maxpay and instead of standard validation
     * redirects to standard Maxpay dispatcher
     *
     * @return bool
     */
    public function changeWrapping()
    {
        $return = parent::changeWrapping();

        // in case user adds wrapping, basket info must be resubmitted.
        if ($this->isMaxpay()) {
            $session = \OxidEsales\Eshop\Core\Registry::getSession();
            $maxpayType = (int) $session->getVariable("maxpay");

            if ($maxpayType == 1) {
                $return = "payment";
            } else {
                $return = "basket";
            }
        }

        return $return;
    }
}
