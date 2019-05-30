<?php

namespace Keros\Tools;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class LoggerBuilder
{
    static function createLogger()
    {
        $logger = new \Monolog\Logger('Keros');
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