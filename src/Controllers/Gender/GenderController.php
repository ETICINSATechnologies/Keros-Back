<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/07/2018
 * Time: 15:59
 */

namespace Keros\Controllers\Gender;

use Keros\Entities\Core\Gender;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Gender\GenderService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class GenderController
{
    /**
     * @var GenderService
     */
    private $GenderService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->GenderService = new GenderService($container);
    }
    /**
     * @return Response containing one Gender if it exists
     * @throws KerosException if the validation fails
     */
    public function getGender(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting cat by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $Gender = $this->GenderService->getOne($id);
        if (!$Gender) {
            throw new KerosException("The Gender could not be found", 400);
        }
        return $response->withJson($Gender, 200);
    }



    /**
     * @return Response containing genders
     * @throws KerosException if an unknown error occurs
     */
    public function getAllGenders(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all genders from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Gender::getSearchFields());
        $departments = $this->GenderService->getAll($params);
        return $response->withJson($departments, 200);
    }

}