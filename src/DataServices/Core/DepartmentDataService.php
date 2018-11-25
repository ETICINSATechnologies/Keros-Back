<?php

namespace Keros\DataServices\Core;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Department;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class DepartmentDataService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(Department::class);
    }

    public function getOne(int $id): ?Department
    {
        try {
            $department = $this->repository->find($id);
            return $department;
        } catch (Exception $e) {
            $msg = "Error finding department with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $departments = $this->repository->findAll();
            return $departments;
        } catch (Exception $e) {
            $msg = "Error finding departments : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}