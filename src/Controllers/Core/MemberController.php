<?php
namespace Keros\Controllers\Core;

use Keros\Entities\core\Member;
use Keros\Entities\core\User;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class MemberController
{
    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->memberService = $container->get(MemberService::class);
        $this->userService = $container->get(UserService::class);
    }

    /**
     * @return Response containing one member if it exists
     * @throws KerosException if the validation fails
     */
    public function getMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting member by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);
        $member = $this->memberService->getOne($id);
        if (!$member) {
            throw new KerosException("The member could not be found", 400);
        }
        return $response->withJson($member, 200);
    }

    /**
     * @return Response containing the created member
     * @throws KerosException if the validation fails or the member cannot be created
     */
    public function createMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $username = Validator::name($body["username"]);
        $password = Validator::password($body["password"]);
        $createdAt = Validator::date($body["createdAt"]);
        $disabled = Validator::bool($body["disabled"]);
        $expiresAt = Validator::date($body["expiresAt"]);

        $line1 = Validator::name($body["line1"]);
        $line2 = Validator::name($body["line2"]);
        $postalCode = Validator::float($body["postalCode"]);
        $city = Validator::name($body["city"]);
        $countryId = Validator::float($body["countryId"]);
        $address = new Address($line1, $line2, $postalCode, $city);
        $this->addressService->create($address, $countryId);

        $member = new User($username, $password, null, $createdAt, $disabled, $expiresAt);
        $this->memberService->create($member);

        return $response->withJson($member, 201);
    }

    /**
     * @return Response containing a page of members
     * @throws KerosException if an unknown error occurs
     */
    public function getPageMembers(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page members from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Member::getSearchFields());
        $members = $this->memberService->getMany($params);
        $totalCount = $this->memberService->getCount($params);
        $page = new Page($members, $params, $totalCount);
        return $response->withJson($page, 200);
    }
}