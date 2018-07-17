<?php


namespace Keros\Services\Ua;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;

use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\FirmType;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
class FirmTypeService
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

    public function getMany(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try {
            $firmTypes = $this->repository->matching($criteria)->getValues();
            return $firmTypes;
        } catch (Exception $e) {
            $msg = "Error finding Firm Types : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }



}