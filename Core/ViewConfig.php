<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * ViewConfig class wrapper for PayPal module.
 *
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    /** @var null \OxidEsales\PayPalModule\Core\Config */
    protected $payPalConfig = null;

    /**
     * PayPal payment object.
     *
     * @var \OxidEsales\Eshop\Application\Model\Payment|bool
     */
    protected $payPalPayment = null;

    /**
     * Status if Express checkout is ON.
     *
     * @var bool
     */
    protected $expressCheckoutEnabled = null;

    /**
     * Status if Standard checkout is ON.
     *
     * @var bool
     */
    protected $standardCheckoutEnabled = null;

    /**
     * Status if PayPal is ON.
     *
     * @var bool
     */
    protected $payPalEnabled = null;

    /**
     * PayPal Payment Validator object.
     *
     * @var \OxidEsales\PayPalModule\Model\PaymentValidator
     */
    protected $paymentValidator = null;

    /**
     * Set \OxidEsales\PayPalModule\Model\PaymentValidator.
     *
     * @param \OxidEsales\PayPalModule\Model\PaymentValidator $paymentValidator
     */
    public function setPaymentValidator($paymentValidator)
    {
        $this->paymentValidator = $paymentValidator;
    }

    /**
     * Get \OxidEsales\PayPalModule\Model\PaymentValidator. Create new if does not exist.
     *
     * @return \OxidEsales\PayPalModule\Model\PaymentValidator
     */
    public function getPaymentValidator()
    {
        if (is_null($this->paymentValidator)) {
            $this->setPaymentValidator(oxNew(\OxidEsales\PayPalModule\Model\PaymentValidator::class));
        }

        return $this->paymentValidator;
    }

    /**
     * Checks if PayPal standard or express checkout is enabled.
     * Does not do payment amount or user country/group check.
     *
     * @return bool
     */
    public function isPayPalActive()
    {
        return $this->getPaymentValidator()->isPaymentActive();
    }

    /**
     * Returns PayPal payment description text.
     *
     * @return string
     */
    public function getPayPalPaymentDescription()
    {
        $desc = "";
        if (($payPalPayment = $this->getPayPalPayment())) {
            $desc = $payPalPayment->oxpayments__oxlongdesc->getRawValue();
        }

        return $desc;
    }

    /**
     * Returns PayPal payment object.
     *
     * @return \OxidEsales\Eshop\Application\Model\Payment
     */
    public function getPayPalPayment()
    {
        if ($this->payPalPayment === null) {
            $this->payPalPayment = false;
            $payPalPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);

            // payment is not available/active?
            if ($payPalPayment->load("oxidpaypal") && $payPalPayment->oxpayments__oxactive->value) {
                $this->payPalPayment = $payPalPayment;
            }
        }

        return $this->payPalPayment;
    }

    /**
     * Returns PayPal config.
     *
     * @return \OxidEsales\PayPalModule\Core\Config
     */
    protected function getPayPalConfig()
    {
        if (is_null($this->payPalConfig)) {
            $this->payPalConfig = oxNew(\OxidEsales\PayPalModule\Core\Config::class);
        }

        return $this->payPalConfig;
    }

    /**
     * Returns current URL.
     *
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->getPayPalConfig()->getCurrentUrl();
    }
}
