<?php

namespace Keros\Error;

use Exception;

class KerosException extends Exception
{
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