<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Entities\core\Member;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\UserService;
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

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
        $this->memberService = $container->get(MemberService::class);
    }

    public function getMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting member by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $member = $this->memberService->getOne($args["id"]);

        return $response->withJson($member, 200);
    }

    public function getPageMembers(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page members from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Member::getSearchFields());

        $members = $this->memberService->getPage($params);
        $totalCount = $this->memberService->getCount($params);

        $page = new Page($members, $params, $totalCount);
        return $response->withJson($page, 200);
    }

    public function createMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $member = $this->memberService->create($body);
        $this->entityManager->commit();

        return $response->withJson($member, 201);
    }

    public function updateMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $member = $this->memberService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($member, 200);
    }
}