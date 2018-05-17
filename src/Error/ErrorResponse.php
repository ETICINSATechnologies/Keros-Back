<?php
namespace Keros\Error;

use Exception;

class ErrorResponse
{
    public $message;
    public $status;

    public function __construct(KerosException $exception)
    {
        $this->message = $exception->getMessage();
        $this->status = $exception->getStatus();
    }
}