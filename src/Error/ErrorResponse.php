<?php
namespace Keros\Error;

use Exception;

/**
 * Class ErrorResponse. Model for error responses in JSON
 * @package Keros\Error
 */
class ErrorResponse
{
    public $message;
    public $status;

    public function __construct($message, $status)
    {
        $this->message = $message;
        $this->status = $status;
    }
}