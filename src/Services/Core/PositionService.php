<?php

namespace Keros\Services\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Position;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class PositionService
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
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
        $this->repository = $this->entityManager->getRepository(Position::class);
    }

    public function getOne(int $id): ?Position
    {
        try {
            $position = $this->repository->find($id);
            return $position;
        } catch (Exception $e) {
            $msg = "Error finding position with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getMany(RequestParameters $requestParameters): array
    {
        try {
            $positions = $this->repository->findAll();
            return $positions;
        } catch (Exception $e) {
            $msg = "Error finding page of positions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}