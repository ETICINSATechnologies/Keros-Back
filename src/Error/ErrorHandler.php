<?php

namespace Keros\Error;

use Doctrine\ORM\EntityManager;
use Error;
use Exception;
use Keros\Tools\ConfigLoader;
use Keros\Tools\LoggerBuilder;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class ErrorHandler. Handles any exceptions thrown in any requests.
 * @package Keros\Error
 */
class ErrorHandler
{
    /**
     * @var LoggerBuilder
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;

    function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
    }

    /**
     * @param Exception|Error $exception the exception with error info
     * @return mixed a response with details if it's a KerosException, or a generic message
     */
    public function __invoke($request, $response, $exception)
    {
        // Rollback any changes since start of transaction, if there is any
        try {
            $this->entityManager->rollback();
        } catch (Exception $e) {
            // No transaction active, nothing to do
        };
        
        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();
        $callStack = '';
        if(ConfigLoader::getConfig()["isTesting"] === true) {
            $traces = $exception->getTrace();
            $traces = array_slice($traces, 0, 5);
            foreach ($traces as $trace) {
                $callStack .= "\r\n\tat " . (isset($trace['file']) ? $trace['file'] : '') . ' ' . $trace['function'] . ':' . (isset($trace['line']) ? $trace['line'] : '');
            }
        }

        $fullMessage = "File : $file. Line : $line. Message : $message.$callStack";
        $this->logger->error($fullMessage);

        if ($exception instanceof KerosException) {
            $errorResponse = new ErrorResponse(new KerosException($fullMessage, $exception->getStatus()));
        } else {

            $errorResponse = new ErrorResponse(new KerosException("Internal Service Error. " . $fullMessage, 500));
        }
        return $response
            ->withStatus($errorResponse->status)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($errorResponse));
    }
}