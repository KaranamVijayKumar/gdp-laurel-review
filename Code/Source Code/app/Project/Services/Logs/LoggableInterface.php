<?php
/**
 * File: LoggableInterface.php
 * Created: 04-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Services\Logs;

use Story\Collection;

/**
 * Interface LoggableInterface
 * @package Project\Services\Logs
 */
interface LoggableInterface
{
    /**
     * Load and returns the logs
     *
     * @return Collection
     */
    public function getLogs();
}
