<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13/07/2018
 * Time: 14:58
 */

namespace Keros\Controllers\Firm_type;


use Keros\Entities\Ua\Firm_type;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Firm_type\Firm_typeService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Firm_typeController
{
    /**
     * @var Firm_typeService
     */
    private $Firm_typeService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->Firm_typeService = new Firm_typeService($container);
    }

    /**
     * @return Response containing one Firm_Type if it exists
     * @throws KerosException if the validation fails
     */
    public function getFirm_Type(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting Firm_Type by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $cat = $this->Firm_typeService->getOne($id);
        if (!$cat) {
            throw new KerosException("The Firm_Type could not be found", 400);
        }
        return $response->withJson($cat, 200);
    }



    /**
     * @return Response containing Firm_Types
     * @throws KerosException if an unknown error occurs
     */
    public function getAllFirm_type(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get Firm_types from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Firm_type::getSearchFields());

        $Firm_types = $this->Firm_typeService->getMany($params);


        return $response->withJson($Firm_types, 200);
    }
}