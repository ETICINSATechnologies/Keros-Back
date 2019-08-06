<?php

namespace Keros\Controllers\Sg;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Sg\ConsultantInscriptionService;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ConsultantInscriptionController
{
    /**
     * @var ConsultantInscriptionService
     */
    private $consultantInscriptionService;

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
        $this->consultantInscriptionService = $container->get(ConsultantInscriptionService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultantInscription = $this->consultantInscriptionService->getOne($args["id"]);

        return $response->withJson($consultantInscription, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getPageConsultantInscriptions(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page consultantInscriptions from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, ConsultantInscription::getSearchFields());

        $consultantInscriptions = $this->consultantInscriptionService->getPage($params);
        $count = $this->consultantInscriptionService->getCount($params);

        $page = new Page($consultantInscriptions, $params, $count);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function createConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->create($body);
        $this->entityManager->commit();

        return $response->withJson($consultantInscription, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function updateConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($consultantInscription, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function deleteConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->consultantInscriptionService->delete($args['id']);
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
    public function validateConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->consultantInscriptionService->validateConsultantInscription($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }
}