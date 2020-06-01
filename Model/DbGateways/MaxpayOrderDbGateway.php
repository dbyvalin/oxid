<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Model\DbGateways;

/**
 * Order db gateway class
 */
class MaxpayOrderDbGateway extends \Maxpay\MaxpayModule\Core\ModelDbGateway
{
    /**
     * Save Maxpay order data to database.
     *
     * @param array $data
     *
     * @return bool
     */
    public function save($data)
    {
        $db = $this->getDb();
        $fields = [];

        foreach ($data as $field => $value) {
            $fields[] = '`' . $field . '` = ' . $db->quote($value);
        }

        $query = 'INSERT INTO `maxpay_order` SET ';
        $query .= implode(', ', $fields);
        $query .= ' ON DUPLICATE KEY UPDATE ';
        $query .= ' `maxpay_orderid`=LAST_INSERT_ID(`maxpay_orderid`), ';
        $query .= implode(', ', $fields);

        $db->execute($query);

        $id = $data['maxpay_orderid'];
        if (empty($id)) {
            $id = $db->getOne('SELECT LAST_INSERT_ID()');
        }

        return $id;
    }

    /**
     * Load Maxpay order data from Db.
     *
     * @param string $orderId Order id.
     *
     * @return array
     */
    public function load($orderId)
    {
        $db = $this->getDb();
        $data = $db->getRow('SELECT * FROM `maxpay_order` WHERE `maxpay_orderid` = ' . $db->quote($orderId));

        return $data;
    }

    /**
     * Delete Maxpay order data from database.
     *
     * @param string $orderId Order id.
     *
     * @return bool
     */
    public function delete($orderId)
    {
        $db = $this->getDb();
        $db->startTransaction();

        $deleteCommentsResult = $db->execute(
            'DELETE
                `maxpay_orderpaymentcomments`
            FROM `maxpay_orderpaymentcomments`
                INNER JOIN `mapxay_orderpayments` ON `maxpay_orderpayments`.`maxpay_paymentid` = `maxpay_orderpaymentcomments`.`maxpay_paymentid`
            WHERE `maxpay_orderpayments`.`maxpay_orderid` = ' . $db->quote($orderId)
        );
        $deleteOrderPaymentResult = $db->execute('DELETE FROM `maxpay_orderpayments` WHERE `maxpay_orderid` = ' . $db->quote($orderId));
        $deleteOrderResult = $db->execute('DELETE FROM `maxpay_order` WHERE `maxpay_orderid` = ' . $db->quote($orderId));

        $result = ($deleteOrderResult !== false) || ($deleteOrderPaymentResult !== false) || ($deleteCommentsResult !== false);

        if ($result) {
            $db->commitTransaction();
        } else {
            $db->rollbackTransaction();
        }

        return $result;
    }
}
