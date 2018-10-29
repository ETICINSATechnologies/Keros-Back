<?php

namespace Keros\Services\Auth;


use Keros\DataServices\Auth\LoginDataService;
use Keros\Entities\Core\User;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;


class LoginService
{
    private $loginDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->loginDataService = $container->get(LoginDataService::class);
    }

    public function checkLogin(array $fields): ?User
    {
        $username = Validator::requiredString($fields["username"]);
        $password = Validator::requiredPassword($fields["password"]);

        $user = $this->loginDataService->checkLogin($username, $password);
        if (!$user) {
            throw new KerosException("Authentication failed", 401);
        }

        return $user;
    }

    public function encode(array $payload)
    {

    }

    public function decode(String $jwt)
    {

    }
}