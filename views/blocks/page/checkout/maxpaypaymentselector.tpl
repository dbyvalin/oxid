[{if $sPaymentID == "oxidmaxpay"}]
************
    <div class="well well-sm">
        <dl>
            <dt>
                <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
                [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
                <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b></label>
            </dt>
            <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
                [{if $paymentmethod->getPrice() && $paymentmethod->oxpayments__oxaddsum->rawValue != 0}]
                    [{if $oxcmp_basket->getPayCostNet()}]
                        [{$paymentmethod->getFNettoPrice()}] [{$currency->sign}] [{oxmultilang ident="OEPAYPAL_PLUS_VAT"}] [{$paymentmethod->getFPriceVat()}]
                    [{else}]
                        [{$paymentmethod->getFBruttoPrice()}] [{$currency->sign}]
                    [{/if}]
                [{/if}]
                <div class="paypalDescBox">
                    <a href="#"><img class="paypalPaymentImg"
                                     src="[{$oViewConf->getModuleUrl('maxpay','out/img/')}]logo.png"
                                     border="0" alt="logo"></a>
                    [{if $paymentmethod->oxpayments__oxlongdesc|trim}]
                        <div class="paypalPaymentDesc">
                            [{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
                        </div>
                    [{/if}]
                </div>
            </dd>
        </dl>
    </div>
[{elseif $sPaymentID != "oxidmaxpay"}]
=====[{$sPaymentID}]
    [{$smarty.block.parent}]
[{/if}]
