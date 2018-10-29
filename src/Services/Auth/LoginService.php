<?php

namespace Keros\Services\Auth;


use Keros\DataServices\Core\UserDataService;
use Keros\Entities\Auth\LoginResponse;
use Keros\Error\KerosException;
use Keros\Tools\JwtCodec;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;


class LoginService
{
    private $loginDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->loginDataService = $container->get(UserDataService::class);
    }

    public function checkLogin(array $fields): ?LoginResponse
    {
        $username = Validator::requiredString($fields["username"]);
        $password = Validator::requiredPassword($fields["password"]);

        $user = $this->loginDataService->checkLogin($username, $password);

        if ($user) {
            // the token will expire in exactly in one day
            $exp = time() + 24 * 3600;

            // creation of the payload
            $payload = array(
                "username" => $user->getUsername(),
                "exp" => $exp
            );

            // create the token from the payload
            $token = JwtCodec::encode($payload);

            return new LoginResponse($token, $exp);
        }

        throw new KerosException("Authentication failed", 401);
    }

    public function encode(array $payload)
    {

    }

    public function decode(String $jwt)
    {

    }
}