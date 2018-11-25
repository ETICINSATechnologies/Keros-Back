<?php

namespace Keros\DataServices\Ua;



use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Ua\Field;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FieldDataService
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
        $this->repository = $this->entityManager->getRepository(Field::class);
    }

    public function getOne(int $id): ?Field
    {
        try {
            $field = $this->repository->find($id);
            return $field;
        } catch (Exception $e) {
            $msg = "Error finding Field with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $field = $this->repository->findAll();
            return $field;
        } catch (Exception $e) {
            $msg = "Error finding page of field : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}