<?php

namespace Keros\DataServices\Core;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Pole;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class PoleDataService
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
        $this->repository = $this->entityManager->getRepository(Pole::class);
    }

    public function getOne(int $id): ?Pole
    {
        try {
            $pole = $this->repository->find($id);
            return $pole;
        } catch (Exception $e) {
            $msg = "Error finding pole with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $poles = $this->repository->findAll();
            return $poles;
        } catch (Exception $e) {
            $msg = "Error finding page of poles : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}
