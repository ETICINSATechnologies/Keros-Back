<?php

namespace Keros\Tools;

use Monolog\Handler\StreamHandler;

class LoggerBuilder
{
    static function createLogger()
    {
        $logger = new \Monolog\Logger('Keros');
        $fileHandler = new StreamHandler(__DIR__ . '/../../logs/app.log', \Monolog\Logger::DEBUG);
        $logger->pushHandler(new StreamHandler('php://stdout', \Monolog\Logger::DEBUG));
        $logger->pushHandler($fileHandler);
        return $logger;
    }
}