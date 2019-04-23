<?php

namespace Keros\Controllers\Treso;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Services\Core\MemberService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\Facture;
use Keros\Services\Treso\FactureDocumentService;
use Keros\Services\Treso\FactureDocumentTypeService;
use Keros\Services\Treso\FactureService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Keros\Error\KerosException;
use Exception;

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

    /**
     * @var FactureDocumentTypeService
     */
    private $factureDocumentTypeService;

    /**
     * @var FactureDocumentService
     */
    private $factureDocumentService;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->factureService = $container->get(FactureService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->factureDocumentTypeService = $container->get(FactureDocumentTypeService::class);
        $this->factureDocumentService = $container->get(FactureDocumentService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting facture by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $facture = $this->factureService->getOne($args["id"]);

        return $response->withJson($this->addDocumentsToJsonFacture($facture), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getAllFactures(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get factures " . $request->getServerParams()["REMOTE_ADDR"]);

        $factures = $this->factureService->getAll();
        $facturesWithDocument = array();
        foreach ($factures as $facture)
            $facturesWithDocument[] = $this->addDocumentsToJsonFacture($facture);

        return $response->withJson($facturesWithDocument, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getPageFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page factures from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Facture::getSearchFields());

        $factures = $this->factureService->getPage($params);
        $totalCount = $this->factureService->getCount($params);

        $facturesWithDocument = array();
        foreach ($factures as $facture) {
            $facturesWithDocument[] = $this->addDocumentsToJsonFacture($facture);
        }

        $page = new Page($facturesWithDocument, $params, $totalCount);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function createFacture(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();
        $body["createdBy"] = $request->getAttribute("userId");
        $this->entityManager->beginTransaction();
        $facture = $this->factureService->create($body);
        $this->entityManager->commit();

        return $response->withJson($this->addDocumentsToJsonFacture($facture), 201);
    }

    public function deleteFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting facture from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->entityManager->beginTransaction();
        $this->factureService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function updateFacture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating facture from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $facture = $this->factureService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($this->addDocumentsToJsonFacture($facture), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function validateFactureByUa(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating facture by Ua from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->factureService->validateByUa($args['id'], $request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function validateFactureByPerf(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating facture by Ua from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->factureService->validateByPerf($args['id'], $request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    /**
     * @param Facture $facture
     * @return array
     * @throws KerosException
     */
    private function addDocumentsToJsonFacture(Facture $facture)
    {
        $factureWithDocument = array();
        foreach ($facture->jsonSerialize() as $key => $value) {
            $factureWithDocument[$key] = $value;
        }
        $factureWithDocument['documents'] = array();
        foreach ($this->factureDocumentTypeService->getAll() as $factureDocumentType) {
            $factureWithDocument['documents'][] = array(
                'id' => $factureDocumentType->getId(),
                'name' => $factureDocumentType->getName(),
                'isTemplatable' => $factureDocumentType->getisTemplatable(),
                'isUploaded' => $this->factureDocumentService->documentTypeIsUploadedForFacture($factureDocumentType->getId(), $facture->getId())
            );
        }
        return $factureWithDocument;
    }
}