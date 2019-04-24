<?php

namespace Keros\DataServices\Sg;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class   MemberInscriptionDataService
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
     * MemberInscriptionDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(MemberInscription::class);
    }

    /**
     * @param MemberInscription $memberInscription
     * @throws KerosException
     */
    public function persist(MemberInscription $memberInscription): void
    {
        try {
            $this->entityManager->persist($memberInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to persist   MemberInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param MemberInscription $memberInscription
     * @throws KerosException
     */
    public function delete(MemberInscription $memberInscription): void
    {
        try {
            $this->entityManager->remove($memberInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to delete   MemberInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return MemberInscription[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $memberInscriptions = $this->repository->findAll();
            return $memberInscriptions;
        } catch (Exception $e) {
            $msg = "Error finding page of memberInscriptions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return MemberInscription|null
     * @throws KerosException
     */
    public function getOne(int $id): ?MemberInscription
    {
        try {
            $memberInscription = $this->repository->find($id);
            return $memberInscription;
        } catch (Exception $e) {
            $msg = "Error finding memberInscription with ID $id : " . $e->getMessage();
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
            $memberInscriptions = $this->repository->matching($criteria)->getValues();
            return $memberInscriptions;
        } catch (Exception $e) {
            $msg = "Error finding page of memberInscriptions : " . $e->getMessage();
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