<?php

namespace Keros\Tools;

use Monolog\Handler\StreamHandler;

class Logger
{
    static function createLogger()
    {
        $logger = new \Monolog\Logger('Keros');
        $fileHandler = new StreamHandler(__DIR__ . '/../../logs/app.log', \Monolog\Logger::INFO);
        $logger->pushHandler(new StreamHandler('php://stdout', \Monolog\Logger::INFO));
        $logger->pushHandler($fileHandler);
        return $logger;
    }
}