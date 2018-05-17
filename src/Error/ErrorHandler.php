<?php

namespace Keros\Error;

use Exception;
use Keros\Tools\Logger;

/**
 * Class ErrorHandler. Handles any exceptions thrown in any requests.
 * @package Keros\Error
 */
class ErrorHandler {

    /**
     * @param Exception $exception the exception with error info
     * @return mixed a response with details if it's a KerosException, or a generic message
     */
    public function __invoke($request, $response, Exception $exception) {
        Logger::getLogger()->error($exception->getMessage());
        if($exception instanceof KerosException){
            $error = new ErrorResponse($exception);
        } else {
            $error = new ErrorResponse(new KerosException("Internal Service Error", 500));
        }
        return $response
            ->withStatus($error->status)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($error));
    }
}