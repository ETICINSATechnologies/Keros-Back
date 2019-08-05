<?php

namespace Keros\DataServices\Sg;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class   ConsultantInscriptionDataService
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
     * ConsultantInscriptionDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(ConsultantInscription::class);
    }

    /**
     * @param ConsultantInscription $consultantInscription
     * @throws KerosException
     */
    public function persist(ConsultantInscription $consultantInscription): void
    {
        try {
            $this->entityManager->persist($consultantInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to persist ConsultantInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param ConsultantInscription $consultantInscription
     * @throws KerosException
     */
    public function delete(ConsultantInscription $consultantInscription): void
    {
        try {
            $this->entityManager->remove($consultantInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to delete ConsultantInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return ConsultantInscription[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $consultantInscriptions = $this->repository->findAll();
            return $consultantInscriptions;
        } catch (Exception $e) {
            $msg = "Error finding page of consultantInscriptions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return ConsultantInscription|null
     * @throws KerosException
     */
    public function getOne(int $id): ?ConsultantInscription
    {
        try {
            $consultantInscription = $this->repository->find($id);
            return $consultantInscription;
        } catch (Exception $e) {
            $msg = "Error finding consultantInscription with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $consultantInscriptions = $this->repository->matching($criteria)->getValues();
            return $consultantInscriptions;
        } catch (Exception $e) {
            $msg = "Error finding page of consultantInscriptions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters|null $requestParameters
     * @return int
     */
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