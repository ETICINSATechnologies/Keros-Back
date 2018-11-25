<?php


namespace Keros\Controllers\Ua;


use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\FirmType;
use Keros\Services\Ua\FirmTypeService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FirmTypeController
{
    /**
     * @var FirmTypeService
     */
    private $firmTypeService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->firmTypeService = $container->get(FirmTypeService::class);
    }

    public function getFirmType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting Firm_Type by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $firmType = $this->firmTypeService->getOne($args["id"]);
        
        return $response->withJson($firmType, 200);
    }

    public function getAllFirmType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get Firm_types from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, FirmType::getSearchFields());

        $firmTypes = $this->firmTypeService->getPage($params);

        return $response->withJson($firmTypes, 200);
    }
}
