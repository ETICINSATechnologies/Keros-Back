<?php

namespace Keros\Controllers\Sg;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Sg\MemberInscription;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Sg\MemberInscriptionService;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MemberInscriptionController
{
    /**
     * @var MemberInscriptionService
     */
    private $memberInscriptionService;

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
        $this->memberInscriptionService = $container->get(MemberInscriptionService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting memberInscription by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $memberInscription = $this->memberInscriptionService->getOne($args["id"]);

        return $response->withJson($memberInscription, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getPageMemberInscriptions(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page memberInscriptions from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, MemberInscription::getSearchFields());

        $memberInscriptions = $this->memberInscriptionService->getPage($params);
        $count = $this->memberInscriptionService->getCount($params);

        $page = new Page($memberInscriptions, $params, $count);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function createMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating memberInscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $memberInscription = $this->memberInscriptionService->create($body);
        $this->entityManager->commit();

        return $response->withJson($memberInscription, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function updateMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating memberInscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $memberInscription = $this->memberInscriptionService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($memberInscription, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function deleteMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting memberInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberInscriptionService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function validateMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating memberInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberInscriptionService->validateMemberInscription($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }
}