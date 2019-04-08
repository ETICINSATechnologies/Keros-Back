<?php


namespace Keros\Controllers\Treso;

use Keros\Services\Treso\FactureTypeService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FactureTypeController
{
    /**
     * @var FactureTypeService
     */
    private $factureTypeService;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * FactureTypeController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->factureTypeService = $container->get(FactureTypeService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws \Keros\Error\KerosException
     */
    public function getAllFactureTypes(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all factureTypes from " . $request->getServerParams()["REMOTE_ADDR"]);

        $factureTypes = $this->factureTypeService->getAll();

        return $response->withJson($factureTypes, 200);
    }

}
