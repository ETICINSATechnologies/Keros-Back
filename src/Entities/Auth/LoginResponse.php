<?php

namespace Keros\Entities\Auth;

use JsonSerializable;

class LoginResponse implements JsonSerializable
{

    protected $token;

    /**
     * LoginResponse constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function jsonSerialize()
    {
        return [
            'token' => $this->getToken(),
        ];
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }
}