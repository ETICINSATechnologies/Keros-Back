<?php


namespace Keros\Controllers\Ua;


use Keros\Entities\Ua\FirmType;

use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Ua\FirmTypeService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FirmTypeController
{
    /**
     * @var FirmTypeService
     */
    private $FirmTypeService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->FirmTypeService = new FirmTypeService($container);
    }

    /**
     * @return Response containing one Firm_Type if it exists
     * @throws KerosException if the validation fails
     */
    public function getFirmType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting Firm_Type by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $FirmType = $this->FirmTypeService->getOne($id);
        if (!$FirmType) {
            throw new KerosException("The Firm_Type could not be found", 400);
        }
        return $response->withJson($FirmType, 200);
    }



    /**
     * @return Response containing Firm_Types
     * @throws KerosException if an unknown error occurs
     */
    public function getAllFirmType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get Firm_types from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, FirmType::getSearchFields());

        $firmTypes = $this->FirmTypeService->getMany($params);


        return $response->withJson($firmTypes, 200);
    }
}
