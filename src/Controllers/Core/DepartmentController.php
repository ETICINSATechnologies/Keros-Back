<?php

namespace Keros\Controllers\Core;

use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\Department;
use Keros\Error\KerosException;
use Keros\DataServices\Core\DepartmentDataService;
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
    private $departmentService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->departmentService = $container->get(DepartmentService::class);
    }

    public function getDepartment(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting Department by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $department = $this->departmentService->getOne($args["id"]);

        return $response->withJson($department, 200);
    }

    public function getAllDepartments(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all departments from " . $request->getServerParams()["REMOTE_ADDR"]);

        $departments = $this->departmentService->getAll();

        return $response->withJson($departments, 200);
    }
}