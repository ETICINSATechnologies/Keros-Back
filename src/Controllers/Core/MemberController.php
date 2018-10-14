<?php

namespace Keros\Controllers\Core;

use Keros\Entities\core\Member;
use Keros\Entities\Core\Page;
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
        $this->addressController = new AddressController($container);
        $this->userController = new UserController($container);
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
        if (!$member)
        {
            throw new KerosException("The member could not be found", 400);
        }
        return $response->withJson($member, 200);
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

    /**
     * @return Response containing the created member
     * @throws KerosException if the validation fails or the member cannot be created
     */
    public function createMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $addressId = $this->addressController->SMCreateAddress($body["address"])->getId();
        $userId = $this->userController->SMcreateUser($body)->getId();
        $member = $this->SMCreateMember($body, $addressId, $userId);

        return $response->withJson($member, 201);
    }

    /**
     * @return Response containing the created member
     * @throws KerosException if the validation fails or the member cannot be created
     */
    public function updateMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $member = $this->SMUpdateMember($body);
        $this->addressController->SMUpdateAddress($member->getAddress()->getId(), $body["address"]);
        $this->userController->SMUpdateUser($member->getId(), $body);

        return $response->withJson($member, 201);
    }

    /* ================= SMA ================*/

    /**
     * @param $body
     * @param $addressId
     * @param $userId
     * @return Member
     * @throws KerosException
     */
    public function SMCreateMember($body, $addressId, $userId)
    {
        $firstName = Validator::name($body["firstName"]);
        $lastName = Validator::name($body["lastName"]);
        $email = Validator::email($body["email"]);
        $telephone = Validator::optionalPhone($body["telephone"]);
        $birthday = Validator::date($body["birthday"]);
        $schoolYear = Validator::schoolYear($body["schoolYear"]);

        $genderId = Validator::id($body["genderId"]);
        $departmentId = Validator::id($body["departmentId"]);

        $this->logger->info($addressId);

        $member = new Member($firstName, $lastName, $birthday, $telephone, $email, $schoolYear);

        $this->memberService->create($member, $userId, $genderId, $departmentId, $addressId);

        return $member;
    }

    /**
     * @param $body
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     * @throws KerosException
     */
    private function SMUpdateMember($body)
    {
        $memberId = Validator::id($body["id"]);
        $firstName = Validator::name($body["firstName"]);
        $lastName = Validator::name($body["lastName"]);
        $email = Validator::email($body["email"]);
        $telephone = Validator::optionalPhone($body["telephone"]);
        $birthday = Validator::date($body["birthday"]);
        $schoolYear = Validator::schoolYear($body["schoolYear"]);

        $genderId = Validator::id($body["genderId"]);
        $departmentId = Validator::id($body["departmentId"]);

        return $this->memberService->update(
            $memberId, $genderId, $departmentId, $firstName, $lastName, $birthday, $telephone, $email, $schoolYear);
    }
}