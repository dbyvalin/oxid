<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Model;

use Maxpay\Lib\Util\SignatureHelper;

/**
 * Payment gateway manager.
 * Checks and sets payment method data, executes payment.
 *
 * @mixin \OxidEsales\Eshop\Application\Model\PaymentGateway
 */
class PaymentGateway extends PaymentGateway_parent
{
    /**
     * Maxpay config.
     *
     * @var null
     */
    protected $maxpayConfig = null;

    /**
     * Maxpay config.
     *
     * @var null
     */
    protected $checkoutService = null;

    /**
     * Order.
     *
     * @var \OxidEsales\Eshop\Application\Model\Order
     */
    protected $maxpayOxOrder;

    /**
     * Sets order.
     *
     * @param \OxidEsales\Eshop\Application\Model\Order $order
     */
    public function setMaxpayOxOrder($order)
    {
        $this->maxpayOxOrder = $order;
    }

    /**
     * Gets order.
     *
     * @return \OxidEsales\Eshop\Application\Model\Order
     */
    public function getMaxpayOxOrder()
    {
        if (is_null($this->maxpayOxOrder)) {
            $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
            $order->loadMaxpayOrder();
            $this->setMaxpayOxOrder($order);
        }

        return $this->maxpayOxOrder;
    }

    /**
     * Executes payment, returns true on success.
     *
     * @param double                               $amount Goods amount
     * @param \OxidEsales\PayPalModule\Model\Order $order  User ordering object
     *
     * @return bool
     */
    public function executePayment($amount, &$order)
    {
        $success = parent::executePayment($amount, $order);
        $session = \OxidEsales\Eshop\Core\Registry::getSession();

        if ( ($session->getVariable('paymentid') == 'oxidmaxpay')
             || ($session->getBasket()->getPaymentId() == 'oxidmaxpay')
        ) {
            $this->setMaxpayOxOrder($order);
            $success = $this->doCheckoutPayment();
        }

        return $success;
    }

    /**
     * Executes "DoCheckoutPayment" to Maxpay
     *
     * @return bool
     */
    public function doCheckoutPayment()
    {
        $order = $this->getMaxpayOrder();
        $orderId = $order->getId();
        $session = \OxidEsales\Eshop\Core\Registry::getSession();

        try {
            
            $config = $this->getMaxpayConfig();
            
            $lang = $config->getLang()->getLanguageAbbr();
            
            $basket = $session->getBasket();
            $user = $this->getUser();
            $userDetails = $user->getDetails();

            $params = [
                'key' => $config->getPublicKey(),
                'uniqueuserid' => $userDetails['customer_id'],
                'email' => $userDetails['email'],
                'firstname' => $userDetails['firstname'],
                'lastname' => $userDetails['lastname'],
                'locale' => $lang . '-' . strtoupper($lang),
                'city' => $userDetails['city'],
                'zip' => $userDetails['zip'],
                'address' => $userDetails['address'],
                'country' => $userDetails['country'],
            ];
            
            if ($order && $orderId) {
                $order->maxpayUpdateOrderNumber();
                
                $params['uniqueTransactionId'] = $orderId;
                $params['customProduct'] = '[' . json_encode([
                    'productType' => 'fixedProduct',
                    'productId'   => $orderId,
                    'productName' => 'Order id #' . $orderId,
                    'currency'    => $basket->getBasketCurrency()->name,
                    'amount'      => $basket->getPriceForPayment(),
                ]) . ']';

                $params['signature'] = (new SignatureHelper())->generateForArray($params, $config->getPrivateKey(), true);
                $params['customProduct'] = htmlspecialchars($params['customProduct']);
            }

        } catch (\OxidEsales\Eshop\Core\Exception\StandardException $excp) {
            return false;
        }
        
        $this->getMaxpayCheckoutService()->redirect($config->getMaxpayHost(), $params);

        return true;
    }


    /**
     * Return Maxpay config
     *
     * @return \Maxpay\MaxpayModule\Core\Config
     */
    public function getMaxpayConfig()
    {
        if (is_null($this->maxpayConfig)) {
            $this->setMaxpayConfig(oxNew(\Maxpay\MaxpayModule\Core\Config::class));
        }

        return $this->maxpayConfig;
    }

    /**
     * Set Maxpay config
     *
     * @param \Maxpay\MaxpayModule\Core\Config $maxpayConfig config
     */
    public function setMaxpayConfig($maxpayConfig)
    {
        $this->maxpayConfig = $maxpayConfig;
    }

    /**
     * Sets Maxpay service
     *
     * @param \Maxpay\MaxpayModule\Core\MaxpayService $checkoutService
     */
    public function setMaxpayCheckoutService($checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Returns Maxpay service
     *
     * @return \Maxpay\MaxpayModule\Core\MaxpayService
     */
    public function getMaxpayCheckoutService()
    {
        if (is_null($this->checkoutService)) {
            $this->setMaxpayCheckoutService(oxNew(\Maxpay\MaxpayModule\Core\MaxpayService::class));
        }

        return $this->checkoutService;
    }

    /**
     * Returns Maxpay order object
     *
     * @return \OxidEsales\Eshop\Application\Model\Order
     */
    protected function getMaxpayOrder()
    {
        return $this->getMaxpayOxOrder();
    }
}
