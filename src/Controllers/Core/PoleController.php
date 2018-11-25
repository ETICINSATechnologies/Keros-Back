<?php

namespace Keros\Controllers\Core;

use Keros\Services\Core\PoleService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PoleController
{
    /**
     * @var PoleService
     */
    private $poleService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->poleService = $container->get(PoleService::class);
    }

    public function getPole(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting pole by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $pole = $this->poleService->getOne($args["id"]);

        return $response->withJson($pole, 200);
    }

    public function getAllPoles(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get poles " . $request->getServerParams()["REMOTE_ADDR"]);

        $poles = $this->poleService->getAll();

        return $response->withJson($poles, 200);
    }
}
