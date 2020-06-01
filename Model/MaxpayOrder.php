<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Model;

/**
 * Maxpay MaxpayOrder class
 */
class MaxpayOrder extends \Maxpay\MaxpayModule\Core\Model
{
    
    public function changeOrderStatus(string $status)
    {
        parent::_setOrderStatus($status);
    }

    /**
     * Return database gateway.
     *
     * @return \Maxpay\MaxpayModule\Model\DbGateways\MaxpayOrderDbGateway|\Maxpay\MaxpayModule\Core\ModelDbGateway
     */
    protected function getDbGateway()
    {
        if (is_null($this->dbGateway)) {
            $this->setDbGateway(oxNew(\Maxpay\MaxpayModule\Model\DbGateways\MaxpayOrderDbGateway::class));
        }

        return $this->dbGateway;
    }
}
