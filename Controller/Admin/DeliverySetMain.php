<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Controller\Admin;

/**
 * Adds additional functionality needed for PayPal when managing delivery sets.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\DeliverySetMain
 */
class DeliverySetMain extends DeliverySetMain_parent
{
    /**
     * Add default PayPal mobile payment.
     *
     * @return string
     */
    public function render()
    {
        $template = parent::render();

        $deliverySetId = $this->getEditObjectId();
        if ($deliverySetId != "-1" && isset($deliverySetId)) {
            /** @var \OxidEsales\PayPalModule\Core\Config $config */
            $config = oxNew(\Maxpay\MaxpayModule\Core\Config::class);

            $isPayPalDefaultMobilePayment = ($deliverySetId == $config->getMobileECDefaultShippingId());

            $this->_aViewData['isPayPalDefaultMobilePayment'] = $isPayPalDefaultMobilePayment;
        }

        return $template;
    }

    /**
     * Saves default PayPal mobile payment.
     */
    public function save()
    {
        parent::save();

        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        /** @var \OxidEsales\PayPalModule\Core\Config $payPalConfig */
        $payPalConfig = oxNew(\Maxpay\MaxpayModule\Core\Config::class);

        $deliverySetId = $this->getEditObjectId();
        $deliverySetMarked = (bool) $config->getRequestParameter('isPayPalDefaultMobilePayment');
        $mobileECDefaultShippingId = $payPalConfig->getMobileECDefaultShippingId();

        if ($deliverySetMarked && $deliverySetId != $mobileECDefaultShippingId) {
            $this->saveECDefaultShippingId($config, $deliverySetId, $payPalConfig);
        } elseif (!$deliverySetMarked && $deliverySetId == $mobileECDefaultShippingId) {
            $this->saveECDefaultShippingId($config, '', $payPalConfig);
        }
    }

    /**
     * Save default shipping id.
     *
     * @param \OxidEsales\Eshop\Core\Config        $config       Config object to save.
     * @param string                               $shippingId   Shipping id.
     * @param \OxidEsales\PayPalModule\Core\Config $payPalConfig PayPal config.
     */
    protected function saveECDefaultShippingId($config, $shippingId, $payPalConfig)
    {
        $payPalModuleId = 'module:' . $payPalConfig->getModuleId();
        $config->saveShopConfVar('string', 'sOEPayPalMECDefaultShippingId', $shippingId, null, $payPalModuleId);
    }
}
