<?php
/**
 * This file is part of OXID Maxpay module.
 */

namespace Maxpay\MaxpayModule\Controller;

/**
 * @mixin \OxidEsales\Eshop\Application\Controller\ThankyouController
 */
class ThankYouController extends ThankYouController_parent
{
    const MAXPAY_PAYMENT_DECLINE_STATUS = 'decline';
    
    public function render()
    {
        return parent::render();
    }
    
    /**
     * Thankyou page post processing.
     * @return boolean
     */
    public function checkOrderProcessing(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }
        
        $transactionStatus = trim(strip_tags($_POST['status']));
        
        $order = $this->getOrder();
        
        $code = trim(strip_tags($_POST['code'] ?? ''));                    
        $message = trim(strip_tags($_POST['message'] ?? ''));
            
        if ($transactionStatus === self::MAXPAY_PAYMENT_DECLINE_STATUS) {
            $order->setOrderErrorStatus('Payment declined. ' . $message . ' (' . $code . ')');
            return false;
        } else {
            $order->setOrderSuccessStatus('Payment processing. ' . $message . ' (' . $code . ')');
        }
        
        return true;
    }
}
