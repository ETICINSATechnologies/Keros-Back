<?php


namespace Keros\DataServices\Treso;


use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Entities\Treso\PaymentSlip;
use Keros\Entities\Treso\Turnover;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class TurnoverDataService
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
        $this->repository = $this->entityManager->getRepository(Turnover::class);
    }

    /**
     * @param Turnover $turnover
     * @throws KerosException
     * @throws ORMException
     * @throws OptimisticLockException
     */

    public function persist(Turnover $turnover): void
    {
        try {
            $this->entityManager->persist($turnover);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to persist Turnover : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param Turnover $turnover
     * @throws KerosException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Turnover $turnover): void
    {
        try {
            $this->entityManager->remove($turnover);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to delete Turnover : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return turnover[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $turnovers = $this->repository->findAll();
            return $turnovers;
        } catch (Exception $e) {
            $msg = "Error finding page of Turnover : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Turnover
    {
        try {
            $turnover = $this->repository->find($id);
            return $turnover;
        } catch (Exception $e) {
            $msg = "Error finding turnover with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param DateTime $time
     * @return Turnover|null
     * @throws KerosException
     */
    public function getByDay(DateTime $time): ?Turnover
    {
        try {
            $turnover = $this->repository->find($time);
            return $turnover;
        } catch (Exception $e) {
            $msg = "Error finding Turnover with DateTime $time : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters|null $requestParameters
     * @return int
     */
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

    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $studies = $this->repository->matching($criteria)->getValues();
            return $studies;
        } catch (Exception $e) {
            $msg = "Error finding page of factures : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }


}