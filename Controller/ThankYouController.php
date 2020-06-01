<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Controller;

/**
 * @mixin \OxidEsales\Eshop\Application\Controller\ThankyouController
 */
class ThankYouController extends ThankYouController_parent
{
    const DECLINE_STATUS = 'decline';
    
    /** Transaction is finished successfully. */
    const MAXPAY_PAYMENT_OK = 'Awaiting Maxpay payment';
    
    /** Transaction is not finished or failed. */
    const MAXPAY_PAYMENT_ERROR = 'ERROR';
    
    public function render()
    {
        return parent::render();
    }
    
    public function checkOrderProcessing()
    {
        if (!$_POST) {
            return false;
        }
        
        $transactionStatus = trim(strip_tags($_POST['status']));
        
        $order = $this->getOrder();
        
        if ($transactionStatus === self::DECLINE_STATUS) {
            
            $order->changeOrderStatus(self::MAXPAY_PAYMENT_ERROR);
            
            $declineMessage = trim(strip_tags($_POST['message']));
            $declineCode = trim(strip_tags($_POST['code']));
            
            $logMessage = 'Payment declined. ' . $declineMessage . ' (' . $declineCode . ')';
            
            return false;
        } else {
            $order->changeOrderStatus(self::MAXPAY_PAYMENT_OK);
        }
        
        return true;
    }
}
