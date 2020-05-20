<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Maxpay Extension checker, check if extension is active on shop/sub-shop
 */
class ExtensionChecker
{
    /**
     * Shop id
     *
     * @var string
     */
    protected $shopId = null;

    /**
     * Extension id
     *
     * @var string
     */
    protected $extensionId = '';

    /**
     * Set shop id
     *
     * @param string $shopId shop id
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * Return shop id
     *
     * @return string
     */
    public function getShopId()
    {
        if (is_null($this->shopId)) {
            $this->setShopId(\OxidEsales\Eshop\Core\Registry::getConfig()->getShopId());
        }

        return $this->shopId;
    }

    /**
     * Set extension id
     *
     * @param string $extensionId extension id
     */
    public function setExtensionId($extensionId)
    {
        $this->extensionId = $extensionId;
    }

    /**
     * Return extension id
     *
     * @return string
     */
    public function getExtensionId()
    {
        return $this->extensionId;
    }

    /**
     * Return return extended classes array
     *
     * @return array
     */
    protected function getExtendedClasses()
    {
        return $this->getConfigValue('aModules');
    }

    /**
     * Return disabled modules array
     *
     * @return array
     */
    protected function getDisabledModules()
    {
        return $this->getConfigValue('aDisabledModules');
    }

    /**
     * Return config value
     *
     * @param string $configName - config parameter name were stored arrays od extended classes
     *
     * @return array
     */
    protected function getConfigValue($configName)
    {
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $configKey = $config->getConfigParam('sConfigKey');

        $select = "SELECT DECODE( `oxvarvalue` , " . $db->quote($configKey) . " ) AS `oxvarvalue` " .
                   "FROM `oxconfig` WHERE `oxvarname` = " . $db->quote($configName) . " AND `oxshopid` = " . $db->quote($this->getShopId());

        return unserialize($db->getOne($select));
    }

    /**
     * Check if module is active.
     *
     * @return  bool
     */
    public function isActive()
    {
        $moduleId = $this->getExtensionId();
        $moduleIsActive = false;

        $modules = $this->getExtendedClasses();

        if (is_array($modules)) {
            // Check if module was ever installed.
            $moduleExists = false;
            foreach ($modules as $extendPath) {
                if (false !== strpos($extendPath, '/' . $moduleId . '/')) {
                    $moduleExists = true;
                    break;
                }
            }

            // If module exists, check if it is not disabled.
            if ($moduleExists) {
                $disabledModules = $this->getDisabledModules();
                if (!(is_array($disabledModules) && in_array($moduleId, $disabledModules))) {
                    $moduleIsActive = true;
                }
            }
        }

        return $moduleIsActive;
    }
}
