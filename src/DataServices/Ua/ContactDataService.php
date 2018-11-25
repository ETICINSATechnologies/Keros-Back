<?php

namespace Keros\DataServices\Ua;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Contact;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class ContactDataService
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
        $this->repository = $this->entityManager->getRepository(Contact::class);
    }

    public function persist(Contact $contact): void
    {
        try {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist contact : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Contact
    {
        try {
            $contact = $this->repository->find($id);
            return $contact;
        } catch (Exception $e) {
            $msg = "Error finding contact with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $members = $this->repository->matching($criteria)->getValues();
            return $members;
        } catch (Exception $e) {
            $msg = "Error finding page of contacts : " . $e->getMessage();
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