<?php

namespace Keros\DataServices\Ua;



use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Ua\Provenance;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class ProvenanceDataService
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
        $this->repository = $this->entityManager->getRepository(Provenance::class);
    }

    public function getOne(int $id): ?Provenance
    {
        try {
            $provenance = $this->repository->find($id);
            return $provenance;
        } catch (Exception $e) {
            $msg = "Error finding Provenance with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $provenance = $this->repository->findAll();
            return $provenance;
        } catch (Exception $e) {
            $msg = "Error finding page of provenance : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}