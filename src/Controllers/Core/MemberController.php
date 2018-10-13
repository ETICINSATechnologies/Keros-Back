<?php
namespace Keros\Controllers\Core;

use Keros\Entities\core\Member;
use Keros\Entities\core\User;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Controllers\Core\AddressController;
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
        $this->adressController = new AddressController($container);
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

        $member = $this->SMCreateMember($body);


        $member->setUser($user);
        $member->setAddress($address);



        return $response->withJson($member, 201);
    }

    /**
     * @param $body
     * @return Member
     * @throws KerosException
     */
    public function SMCreateMember($body)
    {
        $firstName = Validator::name($body["firstName"]);
        $lastName = Validator::name($body["lastName"]);
        $email = Validator::email($body["email"]);
        $telephone = Validator::optionalPhone($body["telephone"]);
        $birthday = Validator::date($body["birthday"]);
        $schoolYear = Validator::schoolYear($body["schoolYear"]);

        $genderId = Validator::id($body["genderId"]);
        $departmentId = Validator::id($body["departmentId"]);
        $addressId = $this->adressController->SMCreateAddress($body["address"])->getId();
        $userId = $this->userController->SMcreateUser($body)->getId();
        $this->logger->info($addressId);

        $member = new Member($firstName, $lastName, $birthday, $telephone, $email, $schoolYear);

        $this->memberService->create($member, $userId, $genderId, $departmentId, $addressId);

        return $member;
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