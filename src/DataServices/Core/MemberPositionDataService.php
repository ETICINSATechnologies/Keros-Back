<?php

namespace Keros\DataServices\Core;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\MemberPosition;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberPositionDataService
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
        $this->repository = $this->entityManager->getRepository(MemberPosition::class);
    }

    public function persist(MemberPosition $memberPosition): void
    {

        try {
            $this->entityManager->persist($memberPosition);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist member position : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $membersPositions = $this->repository->findAll();
            return $membersPositions;
        } catch (Exception $e) {
            $msg = "Error finding page of member positions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?MemberPosition
    {
        try {
            $address = $this->repository->find($id);
            return $address;
        } catch (Exception $e) {
            $msg = "Error finding member position with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $membersPosition = $this->repository->matching($criteria)->getValues();
            return $membersPosition;
        } catch (Exception $e) {
            $msg = "Error finding page of members position : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function delete(MemberPosition $memberPosition): void
    {
        try {
            $this->entityManager->remove($memberPosition);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete member : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}