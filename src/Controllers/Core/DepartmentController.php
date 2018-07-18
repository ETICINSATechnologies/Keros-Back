<?php

namespace Keros\Controllers\Core;

use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\Department;
use Keros\Error\KerosException;
use Keros\Services\Core\DepartmentService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DepartmentController
{
    /**
     * @var DepartmentService
     */
    private $DepartmentService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->DepartmentService = new DepartmentService($container);
    }

    /**
     * @return Response containing one department if it exists
     * @throws KerosException if the validation fails
     */
    public function getDepartment(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting Department by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $Department = $this->DepartmentService->getOne($id);
        if (!$Department) {
            throw new KerosException("The department could not be found", 400);
        }
        return $response->withJson($Department, 200);
    }

    /**
     * @return Response containing all departments
     * @throws KerosException if an unknown error occurs
     */
    public function getAllDepartments(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all departments from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Department::getSearchFields());
        $departments = $this->DepartmentService->getAll($params);
        return $response->withJson($departments, 200);
    }

}