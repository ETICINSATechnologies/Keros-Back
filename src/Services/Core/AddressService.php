<?php

namespace Keros\Services\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\Country;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class AddressService
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
        $this->repository = $this->entityManager->getRepository(Address::class);
    }

    public function create(Address $address, int $countryId)
    {
        $this->entityManager->beginTransaction();
        try {
            $country = $this->entityManager->getReference('Keros\Entities\Core\Country', $countryId);
            $address->setCountry($country);
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $msg = "Failed to create new address : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Address
    {
        try {
            $address = $this->repository->find($id);
            return $address;
        } catch (Exception $e) {
            $msg = "Error finding address with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getMany(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try {
            $addresses = $this->repository->matching($criteria)->getValues();
            return $addresses;
        } catch (Exception $e) {
            $msg = "Error finding page of addresss : " . $e->getMessage();
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