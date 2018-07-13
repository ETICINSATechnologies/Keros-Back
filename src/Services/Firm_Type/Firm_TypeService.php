<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13/07/2018
 * Time: 15:04
 */

namespace Keros\Services\Firm_Type;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;

use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Firm_type;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
class Firm_typeService
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
        $this->repository = $this->entityManager->getRepository(Firm_type::class);
    }




    public function getOne(int $id): ?Firm_type
    {
        try {
            $firm_type = $this->repository->find($id);
            return $firm_type;
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
            $cats = $this->repository->matching($criteria)->getValues();
            return $cats;
        } catch (Exception $e) {
            $msg = "Error finding Firm Types : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }



}