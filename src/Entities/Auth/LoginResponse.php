<?php

namespace Keros\Entities\Auth;


use JsonSerializable;

class LoginResponse implements JsonSerializable
{
    protected $token;

    protected $expiresAt;

    public function __construct(String $token, int $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public function jsonSerialize()
    {
        return [
            'token' => $this->getToken(),
        ];
    }

    /**
     * @return String
     */
    public function getToken(): String
    {
        return $this->token;
    }

    /**
     * @param String $token
     */
    public function setToken(String $token): void
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    /**
     * @param int $expiresAt
     */
    public function setExpiresAt(int $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
}