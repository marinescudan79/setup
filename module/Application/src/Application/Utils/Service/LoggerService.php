<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-03-23 10:06:17
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-23 10:08:48
 */

namespace Application\Utils\Service;

use Application\Utils\Service\AbstractService;
use Zend\Log\Logger;

class LoggerService extends AbstractService
{
    public function infoLog($message)
    {
        $logger = new Logger;
        $logger->log(Logger::INFO, $message);
    }

    public function emergLog($message)
    {
        $logger = new Logger;
        $logger->log(Logger::EMERG, $message);
    }
}
