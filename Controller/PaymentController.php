<?php
/**
 * This file is part of OXID eSales PayPal module.
 *
 * OXID eSales PayPal module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales PayPal module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales PayPal module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2018
 */

namespace Maxpay\MaxpayModule\Controller;

/**
 * Payment class wrapper for PayPal module
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\PaymentController
 */
class PaymentController extends PaymentController_parent
{
    /**
     * Detects is current payment must be processed by PayPal and instead of standard validation
     * redirects to standard PayPal dispatcher
     *
     * @return bool
     */
    public function validatePayment()
    {
        $paymentId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('paymentid');
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $basket = $session->getBasket();
        
        if ($paymentId === 'oxidmaxpay' && $basket->getBruttoSum()) {
            $session->setVariable('paymentid', 'oxidmaxpay');

            return 'maxpaystandarddispatcher?fnc=setCheckout';
        }

        return parent::validatePayment();
    }

}
