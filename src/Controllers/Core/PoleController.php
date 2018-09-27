<?php

namespace Keros\Controllers\Core;

use Keros\Entities\Core\Pole;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\PoleService;
use Keros\Tools\Validator;
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
        $this->logger = $container->get('logger');
        $this->poleService = $container->get(PoleService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing one pole if it exists
     * @throws KerosException if the validation fails
     */
    public function getPole(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting pole by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $pole = $this->poleService->getOne($id);
        if (!$pole) {
            throw new KerosException("The pole could not be found", 400);
        }
        return $response->withJson($pole, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing all poles present in the DB
     * @throws KerosException if an unknown error occurs
     */
    public function getAllPoles(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get poles " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Pole::getSearchFields());

        $poles = $this->poleService->getMany($params);
        return $response->withJson($poles, 200);
    }
}
