<?php

namespace Keros\Controllers\Treso;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Keros\Entities\Treso\Turnover;
use Keros\Error\KerosException;
use Keros\Services\Treso\TurnoverService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\Page;
use Keros\Tools\Validator;

class TurnoverController
{
    /**
     * @var TurnoverService
     */
    private $turnoverService;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * TurnoverController constructor.
     * @param ContainerInterface $container
     */

    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->turnoverService = $container->get(TurnoverService::class);
    }
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getTurnover(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting turnover " . $args['id']. " from " . $request->getServerParams()["REMOTE_ADDR"]);
        echo $args["id"];
        $turnover = $this->turnoverService->getOne($args["id"]);
        return $response->withJson($turnover, 200);
    }
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */

    public function getLatestTurnover(Request $request, Response $response, array $args){
        $this->logger->debug("Getting lastest turnover from " . $request->getServerParams()["REMOTE_ADDR"]);
        $turnover = $this->turnoverService->getLatest();
        return $response->withJson($turnover,200);
    }

    public function deleteTurnover(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting turnover " . $args['id']. " from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->entityManager->beginTransaction();
        $this->turnoverService->delete($args['id']);
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
    public function getAllTurnovers(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting all turnovers from " . $request->getServerParams()["REMOTE_ADDR"]);
        $turnovers = $this->turnoverService->getAll();
        return $response->withJson($turnovers, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createTurnover(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating turnover from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $body = Validator::requiredArray($body);
        $this->entityManager->beginTransaction();
        $turnover = $this->turnoverService->create($body);
        $this->entityManager->commit();
        return $response->withJson($turnover, 201);
    }

    public function getPageTurnover(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page turnovers from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Turnover::getSearchFields());

        $turnover = $this->turnoverService->getPage($params);
        $totalCount = $this->turnoverService->getCount($params);


        $page = new Page($turnover, $params, $totalCount);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws Exception
     */


}