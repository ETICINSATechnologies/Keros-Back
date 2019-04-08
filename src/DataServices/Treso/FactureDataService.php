<?php

namespace Keros\DataServices\Treso;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\Facture;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FactureDataService
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
        $this->repository = $this->entityManager->getRepository(Facture::class);
    }

    public function persist(Facture $facture): void
    {
        try {
            $this->entityManager->persist($facture);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist facture : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function delete(Facture $facture) : void
    {
        try {
            $this->entityManager->remove($facture);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete facture : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $factures = $this->repository->findAll();
            return $factures;
        } catch (Exception $e) {
            $msg = "Error finding page of factures : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Facture
    {
        try {
            $facture = $this->repository->find($id);
            return $facture;
        } catch (Exception $e) {
            $msg = "Error finding facture with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $studies = $this->repository->matching($criteria)->getValues();
            return $studies;
        } catch (Exception $e) {
            $msg = "Error finding page of factures : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getCount(?RequestParameters $requestParameters): int
    {
        if ($requestParameters != null) {
            $countCriteria = $requestParameters->getCountCriteria();
            $count = $this->repository->matching($countCriteria)->count();
        } else {
            $count = $this->repository->matching(Criteria::create())->count();
        }
        return $count;
    }

}