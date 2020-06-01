<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * ViewConfig class wrapper for Maxpay module.
 *
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    /** @var null \Maxpay\MaxpayModule\Core\Config */
    protected $maxpayConfig = null;

    /**
     * Maxpay payment object.
     *
     * @var \OxidEsales\Eshop\Application\Model\Payment|bool
     */
    protected $maxpayPayment = null;

    /**
     * Returns Maxpay payment object.
     *
     * @return \OxidEsales\Eshop\Application\Model\Payment
     */
    public function getMaxpayPayment()
    {
        if ($this->maxpayPayment === null) {
            $this->maxpayPayment = false;
            $maxpayPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);

            // payment is not available/active?
            if ($maxpayPayment->load("oxidmaxpay") && $maxpayPayment->oxpayments__oxactive->value) {
                $this->maxpayPayment = $maxpayPayment;
            }
        }

        return $this->maxpayPayment;
    }

    /**
     * Returns Maxpay config.
     *
     * @return \Maxpay\MaxpayModule\Core\Config
     */
    protected function getMaxpayConfig()
    {
        if (is_null($this->maxpayConfig)) {
            $this->maxpayConfig = oxNew(\Maxpay\MaxpayModule\Core\Config::class);
        }

        return $this->maxpayConfig;
    }
}
