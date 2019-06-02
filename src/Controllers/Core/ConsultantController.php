<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Core\ConsultantService;
use Keros\Tools\Authorization\JwtCodec;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class ConsultantController
{
    /**
     * @var ConsultantService
     */
    private $consultantService;

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

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->consultantService = $container->get(ConsultantService::class);
    }

    public function getConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultant by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultant = $this->consultantService->getOne($args["id"]);

        return $response->withJson($consultant, 200);
    }

    public function getConnectedConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting connected user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultant = $this->consultantService->getOne($request->getAttribute("userId"));

        return $response->withJson($consultant, 200);
    }


    public function updateConnectedConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting updated user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->update($request->getAttribute("userId"), $body);
        $this->entityManager->commit();

        return $response->withJson($consultant, 200);
    }

    public function getPageConsultants(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page consultants from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $requestParameters = new RequestParameters($queryParams, Consultant::getSearchFields());

        $page = $this->consultantService->getPage($requestParameters, $queryParams);

        return $response->withJson($page, 200);
    }

    public function createConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating consultant from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->create($body);
        $this->entityManager->commit();

        return $response->withJson($consultant, 201);
    }

    public function updateConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating consultant from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($consultant, 200);
    }

    public function deleteConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting consultant from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->consultantService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

}