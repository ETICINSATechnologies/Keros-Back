<?php


namespace Keros\DataServices\Core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Gender;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;


class GenderDataService
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
        $this->repository = $this->entityManager->getRepository(Gender::class);
    }

    public function getOne(int $id): ?Gender
    {
        try {
            $gender = $this->repository->find($id);
            return $gender;
        } catch (Exception $e) {
            $msg = "Error finding Gender with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $genders = $this->repository->findAll();
            return $genders;
        } catch (Exception $e) {
            $msg = "Error finding genders : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}
