<?php

namespace Keros\DataServices\Core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Document;
use Keros\Error\KerosException;
use Monolog\Logger;
use Exception;
use Psr\Container\ContainerInterface;

class DocumentDataService
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
     * DocumentDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(Document::class);
    }

    /**
     * @param Document $document
     * @throws KerosException
     */
    public function persist(Document $document): void
    {
        try {
            $this->entityManager->persist($document);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist document : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return Document|null
     * @throws KerosException
     */
    public function getOne(int $id): ?Document
    {
        try {
            $document = $this->repository->find($id);
            return $document;
        } catch (Exception $e) {
            $msg = "Error finding document with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return Document[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $documents = $this->repository->findAll();
            return $documents;
        } catch (Exception $e) {
            $msg = "Error finding page of documents : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param Document $document
     * @throws KerosException
     */
    public function delete(Document $document): void
    {
        try {
            $this->entityManager->remove($document);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete document : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }


}