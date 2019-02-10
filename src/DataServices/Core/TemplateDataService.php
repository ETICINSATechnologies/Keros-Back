<?php

namespace Keros\DataServices\Core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Core\Template;
use Keros\Error\KerosException;
use Monolog\Logger;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerInterface;

class TemplateDataService
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
     * TemplateDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(Template::class);
    }

    /**
     * @param int $id
     * @return Template|null
     * @throws KerosException
     */
    public function getOne(int $id): ?Template
    {
        try {
            $template = $this->repository->find($id);
            return $template;
        } catch (Exception $e) {
            $msg = "Error finding template with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $template = $this->repository->findAll();
            return $template;
        } catch (Exception $e) {
            $msg = "Error finding templates : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }


    /**
     * @param Template $template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(Template $template)
    {
        try {
            $this->entityManager->persist($template);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist template : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param Template $template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Template $template)
    {
        try {
            $this->entityManager->remove($template);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete template : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

}