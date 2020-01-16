<?php

namespace Keros\Tools;

use Doctrine\ORM\EntityManager;
use Keros\Error\ErrorHandler;
use Keros\Tools\Authorization\AuthenticationMiddleware;
use Keros\Tools\Authorization\JwtCodec;
use Keros\Tools\Database\EntityManagerBuilder;
use Keros\Tools\Mail\MailFactory;
use Keros\Tools\Mail\MailSender;
use Monolog\Logger;
use Psr\Container\ContainerInterface;


class ToolRegistrar
{
    public static function register(ContainerInterface $container)
    {
        $container[EntityManager::class] = function () {
            return EntityManagerBuilder::getEntityManager();
        };
        $container[Logger::class] = function () {
            return LoggerBuilder::createLogger();
        };
        $container['errorHandler'] = function ($container) {
            return new ErrorHandler($container);
        };
        $container['phpErrorHandler'] = function ($container) {
            return $container['errorHandler'];
        };
        $container[JwtCodec::class] = function ($container) {
            return new JwtCodec($container);
        };
        $container[AuthenticationMiddleware::class] = function ($container) {
            return new AuthenticationMiddleware($container);
        };
        $container[GenderBuilder::class] = function (){
            return new GenderBuilder();
        };
        $container[DirectoryManager::class] = function ($container){
            return new DirectoryManager($container);
        };
        $container[DocumentGenerator::class] = function ($container){
            return new DocumentGenerator($container);
        };
        $container[MailSender::class] = function ($container){
            return new MailSender($container);
        };
        $container[MailFactory::class] = function ($container){
            return new MailFactory($container);
        };
    }
}