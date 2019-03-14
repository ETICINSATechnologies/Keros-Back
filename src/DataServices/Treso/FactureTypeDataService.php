<?php


namespace Keros\DataServices\Treso;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Treso\FactureType;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;


class FactureTypeDataService
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

    /**
     * FactureTypeDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(FactureType::class);
    }

    /**
     * @param int $id
     * @return FactureType|null
     * @throws KerosException
     */
    public function getOne(int $id): ?FactureType
    {
        try {
            $factureType = $this->repository->find($id);
            return $factureType;
        } catch (Exception $e) {
            $msg = "Error finding FactureType with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return FactureType[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $factureTypes = $this->repository->findAll();
            return $factureTypes;
        } catch (Exception $e) {
            $msg = "Error finding factureTypes : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}
