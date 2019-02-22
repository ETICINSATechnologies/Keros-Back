<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Entities\core\Member;
use Keros\Entities\Core\MemberPosition;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\MemberPositionService;
use Keros\Services\Auth\AccessRightsService;
use Keros\Tools\Authorization\JwtCodec;
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
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var JwtCodec
     */
    private $jwtCodec;
    /**
     * @var MemberPositionService
     */
    private $memberPositionService;
    /**
     * @var AccessRightsService
     */
    private $accessRightsService;

    /**
     * MemberController constructor.
     * @param ContainerInterface $container
     */

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->memberService = $container->get(MemberService::class);
        $this->memberPositionService = $container->get(MemberPositionService::class);

    }

    public function getMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting member by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $member = $this->memberService->getOne($args["id"]);

        return $response->withJson($member, 200);
    }

    public function getConnectedUser(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting connected user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $member = $this->memberService->getOne($request->getAttribute("userId"));

        return $response->withJson($member, 200);
    }


    public function updateConnectedUser(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting updated user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $member = $this->memberService->update($request->getAttribute("userId"), $body);
        $this->entityManager->commit();

        return $response->withJson($member, 200);
    }

    public function getPageMembers(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page members from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $requestParameters = new RequestParameters($queryParams, Member::getSearchFields());

        $page = $this->memberService->getPage($requestParameters, $queryParams);

        return $response->withJson($page, 200);
    }

    public function createMember(Request $request, Response $response, array $args)
    {
        //check access rights
        $this->accessRightsService = new AccessRightsService($this->memberService->getOne($request->getAttribute("userId")));
        $this->accessRightsService->checkPostMember();

        $this->logger->debug("Creating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $body = Validator::requiredArray($body);

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

    public function deleteMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting member from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    public function getLatestBoard(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting latest board from " . $request->getServerParams()["REMOTE_ADDR"]);

        $latestBoard = $this->memberService->getLatestBoard();

        return $response->withJson($latestBoard, 200);
    }
}