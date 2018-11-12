<?php

namespace Keros\Services\Auth;


use Keros\DataServices\Core\UserDataService;
use Keros\Entities\Auth\LoginResponse;
use Keros\Error\KerosException;
use Keros\Tools\JwtCodec;
use Keros\Tools\PasswordEncryption;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;


class LoginService
{
    /**
     * @var UserDataService
     */
    private $userDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->userDataService = $container->get(UserDataService::class);
    }

    public function checkLogin(array $fields): ?LoginResponse
    {
        $username = Validator::requiredString($fields["username"]);
        $password = Validator::requiredPassword($fields["password"]);

        $user = $this->userDataService->findByUsername($username);

        if (is_null($user)) {
            throw new KerosException("Authentication failed", 401);
        }

        if (PasswordEncryption::verify($password, $user->getPassword())) {
            // the token will expire in exactly in one day
            $exp = time() + 24 * 3600;

            // creation of the payload
            $payload = array(
                "id" => $user->getId(),
                "username" => $user->getUsername(),
                "exp" => $exp
            );

            // create the token from the payload
            $token = JwtCodec::encode($payload);

            return new LoginResponse($token, $exp);
        }

        throw new KerosException("Authentication failed", 401);
    }
}