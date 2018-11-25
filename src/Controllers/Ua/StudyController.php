<?php

namespace Keros\Controllers\Ua;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Study;
use Keros\Services\Ua\FieldService;
use Keros\Services\Ua\ProvenanceService;
use Keros\Services\Ua\StatusService;
use Keros\Services\Ua\StudyService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class StudyController
{
    /**
     * @var StudyService
     */
    private $studyService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProvenanceService
     */
    private $provenanceService;
    /**
     * @var FieldService
     */
    private $fieldService;
    /**
     * @var StatusService
     */
    private $statusService;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->studyService = $container->get(StudyService::class);
        $this->provenanceService = $container->get(ProvenanceService::class);
        $this->fieldService = $container->get(FieldService::class);
        $this->statusService = $container->get(StatusService::class);
    }

    public function getStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting study by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $study = $this->studyService->getOne($args["id"]);

        return $response->withJson($study, 200);
    }

    public function getPageStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page studys from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Study::getSearchFields());

        $study = $this->studyService->getPage($params);
        $totalCount = $this->studyService->getCount($params);

        $page = new Page($study, $params, $totalCount);
        return $response->withJson($page, 200);
    }

    public function createStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $this->entityManager->beginTransaction();
        $study = $this->studyService->create($body);
        $this->entityManager->commit();

        return $response->withJson($study, 201);
    }

    public function updateStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $study = $this->studyService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($study, 200);
    }

    public function getProvenance(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting provenance by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $provenance = $this->provenanceService->getOne($args["id"]);

        return $response->withJson($provenance, 200);
    }

    public function getAllProvenances(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get provenances " . $request->getServerParams()["REMOTE_ADDR"]);

        $provenances = $this->provenanceService->getAll();

        return $response->withJson($provenances, 200);
    }

    public function getField(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting field by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $field = $this->fieldService->getOne($args["id"]);

        return $response->withJson($field, 200);
    }

    public function getAllFields(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get fields " . $request->getServerParams()["REMOTE_ADDR"]);

        $fields = $this->fieldService->getAll();

        return $response->withJson($fields, 200);
    }

    public function getStatus(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting status by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $status = $this->statusService->getOne($args["id"]);

        return $response->withJson($status, 200);
    }

    public function getAllStatus(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get status " . $request->getServerParams()["REMOTE_ADDR"]);

        $status = $this->statusService->getAll();

        return $response->withJson($status, 200);
    }
}