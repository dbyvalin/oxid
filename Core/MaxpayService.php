<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Maxpay Service class
 */
class MaxpayService
{
    /**
     * Maxpay Caller.
     *
     * @var \Maxpay\MaxpayModule\Core\Config
     */
    protected $maxpayConfig = null;

    /**
     * Maxpay config setter.
     *
     * @param \Maxpay\MaxpayModule\Core\Config $maxpayConfig
     */
    public function setMaxpayConfig($maxpayConfig)
    {
        $this->maxpayConfig = $maxpayConfig;
    }

    /**
     * Maxpay config getter.
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
     * Executes Maxpay callback request
     *
     * @return string
     */
    public function callbackResponse()
    {
        // cleanup
        $this->getCaller()->setParameter("VERSION", null);
        $this->getCaller()->setParameter("PWD", null);
        $this->getCaller()->setParameter("USER", null);
        $this->getCaller()->setParameter("SIGNATURE", null);

        return $this->getCaller()->getCallBackResponse("CallbackResponse");
    }

    /**
     * Executes "RefundTransaction". Returns response array from Maxpay
     */
    public function refundTransaction()
    {
    }
    
    public function redirect(string $url, array $data, array $headers = [])
    {
    ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <script type="text/javascript">
                function closethisasap() {
                    document.forms["redirectpost"].submit();
                }
            </script>
        </head>
        <body onload="closethisasap();">
        <form name="redirectpost" method="post" action="<?= $url; ?>">
            <?php
            if ( !is_null($data) ) {
                foreach ($data as $k => $v) {
                    echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                }
            }
            ?>
        </form>
        </body>
        </html>
        <?php
        exit;
    }
}
