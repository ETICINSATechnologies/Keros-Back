<?php

namespace Keros\Controllers\Ua;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Study;
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

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
        $this->studyService = $container->get(StudyService::class);
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
}