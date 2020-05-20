<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Class defines what module does on Shop events.
 */
class Events
{

    /**
     * Add Maxpay payment method set EN and DE long descriptions
     */
    public static function addPaymentMethod()
    {
        $paymentDescriptions = array(
            'en' => '<div>When selecting this payment method you are being redirected to Maxpay where you can do your order payment.</div>',
            'de' => '<div>Wenn Sie diese Zahlungsmethode wählen, werden Sie zu Maxpay weitergeleitet, wo Sie die Zahlung Ihrer Bestellung vornehmen können.</div>'
        );

        $payment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        
        if (!$payment->load('oxidmaxpay')) {
            $payment->setId('oxidmaxpay');
            $payment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(1);
            $payment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field('Maxpay');
            $payment->oxpayments__oxaddsum = new \OxidEsales\Eshop\Core\Field(0);
            $payment->oxpayments__oxaddsumtype = new \OxidEsales\Eshop\Core\Field('abs');
            $payment->oxpayments__oxfromboni = new \OxidEsales\Eshop\Core\Field(0);
            $payment->oxpayments__oxfromamount = new \OxidEsales\Eshop\Core\Field(0);
            $payment->oxpayments__oxtoamount = new \OxidEsales\Eshop\Core\Field(10000);

            $language = \OxidEsales\Eshop\Core\Registry::getLang();
            $languages = $language->getLanguageIds();
            
            foreach ($paymentDescriptions as $languageAbbreviation => $description) {
                $languageId = array_search($languageAbbreviation, $languages);
                if ($languageId !== false) {
                    $payment->setLanguage($languageId);
                    $payment->oxpayments__oxlongdesc = new \OxidEsales\Eshop\Core\Field($description);
                    $payment->save();
                }
            }
        }
    }

    /**
     * Check if Maxpay is used for sub-shops.
     *
     * @return bool
     */
    public static function isMaxpayActiveOnSubShops()
    {
        $active = false;
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $extensionChecker = oxNew(\Maxpay\MaxpayModule\Core\ExtensionChecker::class);
        $shops = $config->getShopIds();
        $activeShopId = $config->getShopId();

        foreach ($shops as $shopId) {
            if ($shopId != $activeShopId) {
                $extensionChecker->setShopId($shopId);
                $extensionChecker->setExtensionId('maxpay');
                if ($extensionChecker->isActive()) {
                    $active = true;
                    break;
                }
            }
        }

        return $active;
    }

    /**
     * Disables Maxpay payment method
     */
    public static function disablePaymentMethod()
    {
        $payment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        if ($payment->load('oxidmaxpay')) {
            $payment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(0);
            $payment->save();
        }
    }

    /**
     * Activates Maxpay payment method
     */
    public static function enablePaymentMethod()
    {
        $payment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        $payment->load('oxidmaxpay');
        $payment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $payment->save();
    }

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        // adding record to oxPayment table
        self::addPaymentMethod();

        // enabling Maxpay payment method
        self::enablePaymentMethod();
    }

    /**
     * Delete the basket object, which is saved in the session, as it is an instance of \OxidEsales\PayPalModule\Model\Basket
     * and it is no longer a valid object after the module has been deactivated.
     */
    public static function deleteSessionBasket()
    {
        \OxidEsales\Eshop\Core\Registry::getSession()->delBasket();
    }

    /**
     * Execute action on deactivate event
     *
     * @return null
     */
    public static function onDeactivate()
    {
        // If Maxpay is activated on other sub shops do not remove payment method
        if ('EE' == \OxidEsales\Eshop\Core\Registry::getConfig()->getEdition() && self::isMaxpayActiveOnSubShops()) {
            return;
        }
        self::disablePaymentMethod();
        self::deleteSessionBasket();
    }
}
