<?php

namespace Keros\Controllers\Core;

use Keros\Entities\Core\Position;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\PositionService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PositionController
{
    /**
     * @var PositionService
     */
    private $positionService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->positionService = new PositionService($container);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing one position if it exists
     * @throws KerosException if the validation fails
     */
    public function getPosition(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting position by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $position = $this->positionService->getOne($id);
        if (!$position) {
            throw new KerosException("The position could not be found", 400);
        }
        return $response->withJson($position, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing all positions present in the DB
     * @throws KerosException if an unknown error occurs
     */
    public function getAllPositions(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get positions " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Position::getSearchFields());

        $positions = $this->positionService->getMany($params);
        return $response->withJson($positions, 200);
    }
}
