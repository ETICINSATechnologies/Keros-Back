<?php
/**
 * Created by PhpStorm.
 * User: paulgoux
 * Date: 2019-01-15
 * Time: 22:32
 */

namespace Keros\DataServices\Core;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Core\TemplateType;
use Keros\Error\KerosException;
use Monolog\Logger;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerInterface;

class TemplateTypeDataService
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
     * TemplateTypeDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(TemplateType::class);
    }

    /**
     * @param int $id
     * @return TemplateType|null
     * @throws KerosException
     */
    public function getOne(int $id): ?TemplateType
    {
        try {
            $templateType = $this->repository->find($id);
            return $templateType;
        } catch (Exception $e) {
            $msg = "Error finding TemplateType with ID $id : " . $e->getMessage();
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
            $templateTypes = $this->repository->findAll();
            return $templateTypes;
        } catch (Exception $e) {
            $msg = "Error finding all template types : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}