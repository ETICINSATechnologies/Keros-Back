<?php

namespace Keros\Controllers\Auth;

use Keros\Services\Auth\LoginService;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
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
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->loginService = $container->get(LoginService::class);
    }

    public function login(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Login user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();
        $loginResponse = $this->loginService->checkLogin($body);

        return $response->withJson($loginResponse, 200);
    }
}