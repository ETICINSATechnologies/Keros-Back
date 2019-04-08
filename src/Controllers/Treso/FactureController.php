<?php

namespace Keros\Controllers\Treso;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Services\Core\MemberService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\Facture;
use Keros\Services\Treso\FactureService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class FactureController
{
    /**
     * @var FactureService
     */
    private $factureService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var
     */
    private $kerosConfig;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->factureService = $container->get(FactureService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    public function getFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting facture by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $facture = $this->factureService->getOne($args["id"]);

        return $response->withJson($facture, 200);
    }

    public function getAllFactures(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get factures " . $request->getServerParams()["REMOTE_ADDR"]);

        $studies = $this->factureService->getAll();

        return $response->withJson($studies, 200);
    }

    public function getPageFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page factures from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Facture::getSearchFields());

        $facture = $this->factureService->getPage($params);
        $totalCount = $this->factureService->getCount($params);

        $page = new Page($facture, $params, $totalCount);

        return $response->withJson($page, 200);
    }

    public function createFacture(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();
        $body["createdBy"] = $request->getAttribute("userId");
        $this->entityManager->beginTransaction();
        $facture = $this->factureService->create($body);
        $this->entityManager->commit();

        return $response->withJson($facture, 201);
    }

    public function deleteFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting facture from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->entityManager->beginTransaction();
        $this->factureService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    public function updateFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating facture from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $facture = $this->factureService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($facture, 200);
    }

    public function validateFactureByUa(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating facture by Ua from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->factureService->validateByUa($args['id'], $request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    public function validateFactureByPerf(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating facture by Ua from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->factureService->validateByPerf($args['id'], $request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

}