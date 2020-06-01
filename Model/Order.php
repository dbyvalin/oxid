<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Model;

/**
 * Maxpay oxOrder class
 *
 * @mixin \OxidEsales\Eshop\Application\Model\Order
 */
class Order extends Order_parent
{
    /**
     * Maxpay order information
     *
     * @var \Maxpay\MaxpayModule\Model\MaxpayOrder
     */
    protected $maxpayOrder = null;

    /**
     * Loads order associated with current Maxpay order
     *
     * @return bool
     */
    public function loadMaxpayOrder()
    {
        $orderId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable("sess_challenge");

        // if order is not created yet - generating it
        if ($orderId === null) {
            $orderId = \OxidEsales\Eshop\Core\UtilsObject::getInstance()->generateUID();
            $this->setId($orderId);
            $this->save();
            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable("sess_challenge", $orderId);
        }

        return $this->load($orderId);
    }

    /**
     * Updates order number.
     *
     * @return bool
     */
    public function maxpayUpdateOrderNumber()
    {
        if ($this->oxorder__oxordernr->value) {
            $updated = (bool) oxNew(\OxidEsales\Eshop\Core\Counter::class)->update($this->_getCounterIdent(), $this->oxorder__oxordernr->value);
        } else {
            $updated = $this->_setNumber();
        }

        return $updated;
    }

    /**
     * Updates order transaction status, ID and date.
     *
     * @param string $transactionId Order transaction ID
     */
    protected function setPaymentInfoMaxpayOrder($transactionId)
    {
        // set transaction ID and payment date to order
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $query = 'update oxorder set oxtransid=' . $db->quote($transactionId) . ' where oxid=' . $db->quote($this->getId());
        $db->execute($query);

        //updating order object
        $this->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field($transactionId);
    }

    /**
     * Finalizes Maxpay order.
     *
     * @param \OxidEsales\Eshop\Application\Model\Basket                               $basket          Basket object.
     */
    public function finalizeMaxpayOrder($basket)
    {
        $maxpayOrder = $this->getMaxpayOrder();
        $maxpayOrder->setPaymentStatus(self::MAXPAY_PAYMENT_AWAITING);
        $maxpayOrder->save();
    }

    /**
     * Paypal specific status checking.
     *
     * If status comes as OK, lets check real paypal payment state,
     * and if really ok, so lets set it, otherwise dont change status.
     *
     * @param string $status order transaction status
     */
    protected function _setOrderStatus($status)
    {
        $paymentTypeObject = $this->getPaymentType();
        $paymentType = $paymentTypeObject ? $paymentTypeObject->getFieldData('oxpaymentsid') : null;
        if ($paymentType != 'oxidpaypal' || $status != self::OEPAYPAL_TRANSACTION_STATUS_OK) {
            parent::_setOrderStatus($status);
        }
    }

    /**
     * Update order oxpaid to current time.
     */
    public function markOrderPaid()
    {
        parent::_setOrderStatus(self::MAXPAY_PAYMENT_OK);

        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $utilsDate = \OxidEsales\Eshop\Core\Registry::getUtilsDate();
        $date = date('Y-m-d H:i:s', $utilsDate->getTime());

        $query = 'update oxorder set oxpaid=? where oxid=?';
        $db->execute($query, array($date, $this->getId()));

        //updating order object
        $this->oxorder__oxpaid = new \OxidEsales\Eshop\Core\Field($date);
    }
    
    public function changeOrderStatus(string $status)
    {
        parent::_setOrderStatus($status);
    }
    
    /**
     * Checks if delivery set used for current order is available and active.
     * Throws exception if not available
     *
     * @param \OxidEsales\Eshop\Application\Model\Basket $basket basket object
     *
     * @return int
     */
    public function validateDelivery($basket)
    {
        if ($basket->getPaymentId() == 'oxidpaypal') {
            $shippingId = $basket->getShippingId();
            $basketPrice = $basket->getPrice()->getBruttoPrice();
            $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
            if (!$user->loadUserPayPalUser()) {
                $user = $this->getUser();
            }

            $validState = null;
            if (!$this->isPayPalPaymentValid($user, $basketPrice, $shippingId)) {
                $validState = self::ORDER_STATE_INVALIDDELIVERY;
            }
        } else {
            $validState = parent::validateDelivery($basket);
        }

        return $validState;
    }

    /**
     * Returns PayPal order object.
     *
     * @param string $oxId
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalOrder|null
     */
    public function getPayPalOrder($oxId = null)
    {
        if (is_null($this->payPalOrder)) {
            $orderId = is_null($oxId) ? $this->getId() : $oxId;
            $order = oxNew(\OxidEsales\PayPalModule\Model\PayPalOrder::class);
            $order->load($orderId);
            $this->payPalOrder = $order;
        }

        return $this->payPalOrder;
    }

    /**
     * Get payment status
     *
     * @return string
     */
    public function getPayPalPaymentStatus()
    {
        return $this->getPayPalOrder()->getPaymentStatus();
    }

    /**
     * Returns PayPal Authorization id.
     *
     * @return string
     */
    public function getAuthorizationId()
    {
        return $this->oxorder__oxtransid->value;
    }

    /**
     * Checks whether PayPal payment is available.
     *
     * @param object $user
     * @param double $basketPrice
     * @param string $shippingId
     *
     * @return bool
     */
    protected function isPayPalPaymentValid($user, $basketPrice, $shippingId)
    {
        $valid = true;

        $payPalPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        $payPalPayment->load('oxidpaypal');
        if (!$payPalPayment->isValidPayment(null, null, $user, $basketPrice, $shippingId)) {
            $valid = $this->isEmptyPaymentValid($user, $basketPrice, $shippingId);
        }

        return $valid;
    }

    /**
     * Checks whether Empty payment is available.
     *
     * @param object $user
     * @param double $basketPrice
     * @param string $shippingId
     *
     * @return bool
     */
    protected function isEmptyPaymentValid($user, $basketPrice, $shippingId)
    {
        $valid = true;

        $emptyPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        $emptyPayment->load('oxempty');
        if (!$emptyPayment->isValidPayment(null, null, $user, $basketPrice, $shippingId)) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * Returns Maxpay order object.
     *
     * @param string $oxId
     *
     * @return \Maxpay\MaxpayModule\Model\MaxpayOrder|null
     */
    public function getMaxpayOrder($oxId = null)
    {
        if (is_null($this->maxpayOrder)) {
            $orderId = is_null($oxId) ? $this->getId() : $oxId;
            $order = oxNew(\Maxpay\MaxpayModule\Model\MaxpayOrder::class);
            $order->load($orderId);
            $this->maxpayOrder = $order;
        }

        return $this->maxpayOrder;
    }
}
