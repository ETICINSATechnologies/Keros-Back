<?php
namespace Keros\Tools;

use Monolog\Handler\StreamHandler;

class Logger
{

    public static function getLogger() {
        static $logger = null;
        if($logger == null) {
            $logger = new \Monolog\Logger('Keros');
            $fileHandler = new StreamHandler(__DIR__.'/../../logs/app.log', \Monolog\Logger::DEBUG);
            $logger->pushHandler(new StreamHandler('php://stdout', \Monolog\Logger::DEBUG));
            $logger->pushHandler($fileHandler);
        }
        return $logger;
    }
}