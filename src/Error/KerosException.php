<?php

namespace Keros\Error;

use Exception;

/**
 * Class KerosException. Exception to throw if a specific status and message is to be returned
 * @package Keros\Error
 */
class KerosException extends Exception
{
    /**
     * @var int Http status sent as response and in body
     */
    private $status;

    public function __construct($message, int $status)
    {
        $this->status = $status;
        parent::__construct($message);
    }

    public function getStatus(): int {
        return $this->status;
    }
}