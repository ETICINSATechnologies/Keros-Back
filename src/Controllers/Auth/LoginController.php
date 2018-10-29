<?php

namespace Keros\Controllers\Auth;

use Keros\Entities\Auth\LoginResponse;
use Keros\Services\Auth\LoginService;
use Doctrine\ORM\EntityManager;
use Keros\Tools\JwtCodec;
use Keros\Tools\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class LoginController
{
    /**
     * @var loginService
     */
    private $loginService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
        $this->loginService = $container->get(LoginService::class);
    }

    public function login(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Login user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();
        $user = $this->loginService->checkLogin($body);

        // the token will expire in exactly in one day
        $exp = time() + 24 * 3600;

        // creation of the payload
        $payload = array(
            "username" => $user->getUsername(),
            "exp" => $exp
        );

        // create the token from the payload
        $token = JwtCodec::encode($payload);

        return $response->withJson(new LoginResponse($token, $exp), 200);
    }
}