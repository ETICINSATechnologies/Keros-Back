<?php

namespace Keros\Services\Ua;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Address;
use Keros\Entities\Ua\Firm;
use Keros\Services\Core\AddressService;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FirmService
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
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
        $this->repository = $this->entityManager->getRepository(Firm::class);
    }

    public function create(Firm $firm,int $typeId, Address $address)
    {
        $this->entityManager->beginTransaction();
        try {

            $type = $this->entityManager->getReference('Keros\Entities\Ua\FirmType', $typeId);
            $firm->setAddress($address);
            $firm->setType($type);
            $this->entityManager->persist($firm);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $msg = "Failed to create new firm : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
    public function update(Firm $firm, int $addressId,int $typeId)
    {
        $this->entityManager->beginTransaction();
        try {
            //$address = $this->entityManager->getReference('Keros\Entities\Core\Address', $addressId);
            $address=new AdresseService($container);
            $type = $this->entityManager->getReference('Keros\Entities\Ua\FirmType', $typeId);
            $firm->setAddress($address);
            $firm->setType($type);
            $this->entityManager->persist($firm);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $msg = "Failed to update new firm : " . $e->getMessage();
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

    public function getMany(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try {
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