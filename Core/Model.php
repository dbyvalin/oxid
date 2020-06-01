<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Abstract model class
 */
abstract class Model
{
    /**
     * Data base gateway.
     *
     * @var \OxidEsales\PayPalModule\Core\ModelDbGateway
     */
    protected $dbGateway = null;

    /**
     * Model data.
     *
     * @var array
     */
    protected $data = null;

    /**
     * Was object information found in database.
     *
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * Set response data.
     *
     * @param array $data model data
     */
    public function setData($data)
    {
        $data = array_change_key_case($data, CASE_LOWER);
        $this->data = $data;
    }

    /**
     * Return response data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return value from data by given key.
     *
     * @param string $key key of data value
     *
     * @return string
     */
    protected function getValue($key)
    {
        $data = $this->getData();

        return $data[$key];
    }

    /**
     * Return value from data by given key.
     *
     * @param string $key   key of data value
     * @param string $value data value
     */
    protected function setValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Returns model database gateway.
     *
     * @var $dbGateway
     */
    abstract protected function getDbGateway();

    /**
     * Set model database gateway.
     *
     * @param \OxidEsales\PayPalModule\Core\ModelDbGateway $dbGateway
     */
    protected function setDbGateway($dbGateway)
    {
        $this->dbGateway = $dbGateway;
    }

    /**
     * Method for model saving (insert and update data).
     *
     * @return int|false
     */
    public function save()
    {
        $id = $this->getDbGateway()->save($this->getData());
        $this->setId($id);

        return $id;
    }

    /**
     * Delete model data from db.
     *
     * @param string $id model id
     *
     * @return bool
     */
    public function delete($id = null)
    {
        if (!is_null($id)) {
            $this->setId($id);
        }

        return $this->getDbGateway()->delete($this->getId());
    }

    /**
     * Method for loading model, if loaded returns true.
     *
     * @param string $id model id
     *
     * @return bool
     */
    public function load($id = null)
    {
        if (!is_null($id)) {
            $this->setId($id);
        }

        $this->isLoaded = false;
        $data = $this->getDbGateway()->load($this->getId());
        if ($data) {
            $this->setData($data);
            $this->isLoaded = true;
        }

        return $this->isLoaded();
    }

    /**
     * Returns whether object information found in database.
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * Abstract method for delete model.
     *
     * @param string $id model id
     */
    abstract public function setId($id);

    /**
     * Abstract method for getting id.
     */
    abstract public function getId();
}
