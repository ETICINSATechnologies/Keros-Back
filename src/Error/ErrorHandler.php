<?php

namespace Keros\Error;

use Exception;
use Keros\Tools\Logger;

class ErrorHandler {
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