<?php

namespace Keros\DataServices\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Ticket;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class TicketDataService
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
        $this->repository = $this->entityManager->getRepository(Ticket::class);
    }

    public function persist(Ticket $ticket): void
    {
        try {
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist ticket : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Ticket
    {
        try {
            $ticket = $this->repository->find($id);
            return $ticket;
        } catch (Exception $e) {
            $msg = "Error finding ticket with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $tickets = $this->repository->matching($criteria)->getValues();
            return $tickets;
        } catch (Exception $e) {
            $msg = "Error finding page of tickets : " . $e->getMessage();
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

    public function delete(Ticket $ticket): void
    {
        try {
            $this->entityManager->remove($ticket);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete ticket : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}