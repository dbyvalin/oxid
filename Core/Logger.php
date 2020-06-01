<?php
/**
 * This file is part of OXID eSales Maxpay module.
 */

namespace Maxpay\MaxpayModule\Core;

/**
 * Base logger class
 */
class Logger
{
    /**
     * Logger session id.
     *
     * @var string
     */
    protected $loggerSessionId;

    /**
     * Log title
     */
    protected $logTitle = '';

    /**
     * Sets logger session id.
     *
     * @param string $id session id
     */
    public function setLoggerSessionId($id)
    {
        $this->loggerSessionId = $id;
    }

    /**
     * Returns loggers session id.
     *
     * @return string
     */
    public function getLoggerSessionId()
    {
        return $this->loggerSessionId;
    }

    /**
     * Returns full log file path.
     *
     * @return string
     */
    protected function getLogFilePath()
    {
        $logDirectoryPath = \OxidEsales\Eshop\Core\Registry::getConfig()->getLogsDir();

        return $logDirectoryPath . 'maxpay.log';
    }

    /**
     * Set log title.
     *
     * @param string $title Log title
     */
    public function setTitle($title)
    {
        $this->logTitle = $title;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->logTitle;
    }

    /**
     * Writes log message.
     *
     * @param mixed $logData logger data
     */
    public function log($logData)
    {
        $handle = fopen($this->getLogFilePath(), "a+");
        if ($handle !== false) {
            if (is_string($logData)) {
                parse_str($logData, $result);
            } else {
                $result = $logData;
            }

            if (is_array($result)) {
                foreach ($result as $key => $value) {
                    if (is_string($value)) {
                        $result[$key] = urldecode($value);
                    }
                }
            }

            fwrite($handle, "======================= " . $this->getTitle() . " [" . date("Y-m-d H:i:s") . "] ======================= #\n\n");
            fwrite($handle, "SESS ID: " . $this->getLoggerSessionId() . "\n");
            fwrite($handle, trim(var_export($result, true)) . "\n\n");
            fclose($handle);
        }

        //resetting log title
        $this->setTitle('');
    }
}
