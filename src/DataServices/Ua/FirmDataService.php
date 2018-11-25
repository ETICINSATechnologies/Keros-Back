<?php

namespace Keros\DataServices\Ua;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Firm;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FirmDataService
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
        $this->repository = $this->entityManager->getRepository(Firm::class);
    }

    public function persist(Firm $firm)
    {

        try {
            $this->entityManager->persist($firm);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist firm : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Firm
    {
        try {
            $firm = $this->repository->find($id);
            return $firm;
        } catch (Exception $e) {
            $msg = "Error finding firm with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $firms = $this->repository->matching($criteria)->getValues();
            return $firms;
        } catch (Exception $e) {
            $msg = "Error finding page of firms : " . $e->getMessage();
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