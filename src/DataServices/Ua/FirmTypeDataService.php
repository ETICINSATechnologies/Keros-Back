<?php


namespace Keros\DataServices\Ua;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\FirmType;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FirmTypeDataService
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
        $this->repository = $this->entityManager->getRepository(FirmType::class);
    }

    public function getOne(int $id): ?FirmType
    {
        try {
            $firmType = $this->repository->find($id);
            return $firmType;
        } catch (Exception $e) {
            $msg = "Error finding Firm_type with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $firmTypes = $this->repository->matching($criteria)->getValues();
            return $firmTypes;
        } catch (Exception $e) {
            $msg = "Error finding Firm Types : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }


}