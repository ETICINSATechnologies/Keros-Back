<?php

namespace Keros\Controllers\Auth;

use Keros\Services\Auth\LoginService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\UserService;
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
     * @var MemberService
     */
    private $memberService;

    /**
     * @var UserService
     */
    private $userService;

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

    public function forgotMemberPassword(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Resetting password" . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();
        $this->memberService->sendTokenForReset($body);


    }

    public function resetPasswordMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating password" . $request->getServerParams()["REMOTE_ADDR"]);

        $param = $request->getQueryParams();

        $fields = $this->loginService->decryptTokenForReset($param);

        $body = $request->getParsedBody();
        $fields["username"] = $this->userService->getOne($fields["id"])->getUsername();
        $fields["password"] = $body["password"];

        $this->userService->update($fields["id"], $fields);

        return $response->withStatus(200);
    }
}