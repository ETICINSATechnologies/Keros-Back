<?php

namespace Keros\Controllers\Core;

use Keros\Services\Core\PositionService;
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
        $this->logger = $container->get(Logger::class);
        $this->positionService = $container->get(PositionService::class);
    }

    public function getPosition(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting position by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $position = $this->positionService->getOne($args["id"]);

        return $response->withJson($position, 200);
    }

    public function getAllPositions(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get positions " . $request->getServerParams()["REMOTE_ADDR"]);

        $positions = $this->positionService->getAll();

        return $response->withJson($positions, 200);
    }
}
