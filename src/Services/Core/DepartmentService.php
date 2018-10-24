<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\DepartmentDataService;
use Keros\Entities\Core\Department;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class DepartmentService
{
    /**
     * @var DepartmentDataService
     */
    private $departmentDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->departmentDataService = $container->get(DepartmentDataService::class);
    }

    public function getOne(int $id): Department
    {
        $id = Validator::requiredId($id);
        $department = $this->departmentDataService->getOne($id);
        if (!$department) {
            throw new KerosException("The department could not be found", 404);
        }
        return $department;
    }

    public function getAll(): array
    {
        return $this->departmentDataService->getAll();
    }
}