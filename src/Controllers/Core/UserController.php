<?php
namespace Keros\Controllers\Core;

use DateTime;
use Keros\Entities\core\User;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\UserService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->userService = $container->get(UserService::class);
    }

    /**
     * @return Response containing one user if it exists
     * @throws KerosException if the validation fails
     */
    public function getUser(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting user by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);
        $user = $this->userService->getOne($id);
        if (!$user) {
            throw new KerosException("The user could not be found", 400);
        }
        return $response->withJson($user, 200);
    }

    /**
     * @return Response containing a page of users
     * @throws KerosException if an unknown error occurs
     */
    public function getPageUsers(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page users from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, User::getSearchFields());
        $users = $this->userService->getMany($params);
        $totalCount = $this->userService->getCount($params);
        $page = new Page($users, $params, $totalCount);
        return $response->withJson($page, 200);
    }

    /**
     * @return Response containing the created user
     * @throws KerosException if the validation fails or the user cannot be created
     */
    public function createUser(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating user from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $user = $this->SMCreateUser($body);

        return $response->withJson($user, 201);
    }

    /* ================= SMA ================*/

    /**
     * @param $body
     * @return User
     * @throws KerosException
     */
    public function SMCreateUser($body)
    {
        $username = Validator::name($body["username"]);
        $password = Validator::password($body["password"]);
        $disabled = Validator::optionalBool($body["disabled"]);
        $createdAt = new DateTime("now");
        $expiresAt = null;

        $user = new User($username, $password, null, $createdAt, $disabled, $expiresAt);

        $this->userService->create($user);

        return $user;
    }

    /**
     * @param $userId
     * @param $body
     * @throws KerosException
     */
    public function SMUpdateUser($userId, $body)
    {
        $username = Validator::name($body["username"]);
        $password = Validator::password($body["password"]);

        $this->userService->update($userId, $username, $password);
    }
}