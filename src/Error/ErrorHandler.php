<?php

namespace Keros\Error;

use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManager;
use Error;
use Exception;
use Keros\Tools\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class ErrorHandler. Handles any exceptions thrown in any requests.
 * @package Keros\Error
 */
class ErrorHandler
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;

    function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
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
        } catch (ConnectionException $e) {
            // No transaction active, nothing to do
        };

        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();
        $fullMessage = "File : $file. Line : $line. Message : $message.";
        $this->logger->error($fullMessage);

        if ($exception instanceof KerosException) {
            $errorResponse = new ErrorResponse($exception);
        } else {

            $errorResponse = new ErrorResponse(
                new KerosException("Internal Service Error. " . $fullMessage, 500));
        }
        return $response
            ->withStatus($errorResponse->status)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($errorResponse));
    }
}