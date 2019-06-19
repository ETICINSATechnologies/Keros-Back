<?php

namespace Keros\Tools;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use \Monolog\Logger;
use \Exception;

class LoggerBuilder
{
    /**
     * @return Logger
     * @throws Exception
     */
    static function createLogger()
    {
        $logger = new Logger('Keros');
        $fileHandler = new StreamHandler(__DIR__ . '/../../logs/app.log', \Monolog\Logger::DEBUG);
        $stdHandler = new StreamHandler('php://stdout', \Monolog\Logger::DEBUG);
        $formatter = new LineFormatter();
        $formatter->includeStacktraces(true);
        $fileHandler->setFormatter($formatter);
        $stdHandler->setFormatter($formatter);
        $logger->pushHandler($fileHandler);
        $logger->pushHandler($stdHandler);
        return $logger;
    }
}