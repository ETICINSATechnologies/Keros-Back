<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/07/2018
 * Time: 16:06
 */

namespace Keros\Services\Gender;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Gender;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;


class GenderService
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
        $this->repository = $this->entityManager->getRepository(Gender::class);
    }

    public function getOne(int $id): ?Gender
    {
        try {
            $Gender = $this->repository->find($id);
            return $Gender;
        } catch (Exception $e) {
            $msg = "Error finding Gender with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
    public function getAll(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try {
            $genders = $this->repository->matching($criteria)->getValues();
            return $genders;
        } catch (Exception $e) {
            $msg = "Error finding genders : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}