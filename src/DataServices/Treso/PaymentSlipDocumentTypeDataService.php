<?php

namespace Keros\DataServices\Treso;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Treso\PaymentSlipDocumentType;
use Keros\Error\KerosException;
use Monolog\Logger;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerInterface;

class PaymentSlipDocumentTypeDataService
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var EntityRepository */
    protected $repository;

    /** @var Logger */
    protected $logger;

    /**
     * PaymentSlipDocumentTypeDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(PaymentSlipDocumentType::class);
    }

    /**
     * @param int $id
     * @return PaymentSlipDocumentType|null
     * @throws KerosException
     */
    public function getOne(int $id): ?PaymentSlipDocumentType
    {
        try {
            $documentType = $this->repository->find($id);
            return $documentType;
        } catch (Exception $e) {
            $msg = "Error finding document type with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}