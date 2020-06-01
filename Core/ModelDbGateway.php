<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Abstract model db gateway class.
 */
abstract class ModelDbGateway
{
    /**
     * Returns data base resource.
     *
     * @return \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface
     */
    protected function getDb()
    {
        return \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
    }

    /**
     * Abstract method for data saving (insert and update).
     *
     * @param array $data model data
     */
    abstract public function save($data);

    /**
     * Abstract method for loading model data.
     *
     * @param string $id model id
     */
    abstract public function load($id);

    /**
     * Abstract method for delete model data.
     *
     * @param string $id model id
     */
    abstract public function delete($id);
}
