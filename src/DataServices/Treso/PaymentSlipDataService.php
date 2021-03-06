<?php

namespace Keros\DataServices\Treso;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Member;
use Keros\Tools\Validator;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\PaymentSlip;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class PaymentSlipDataService
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
        $this->repository = $this->entityManager->getRepository(PaymentSlip::class);
    }

    /**
     * @param PaymentSlip $paymentSlip
     * @throws KerosException
     */
    public function persist(PaymentSlip $paymentSlip): void
    {
        try {
            $this->entityManager->persist($paymentSlip);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist paymentSlip : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param PaymentSlip $paymentSlip
     * @throws KerosException
     */
    public function delete(PaymentSlip $paymentSlip): void
    {
        try {
            $this->entityManager->remove($paymentSlip);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete paymentSlip : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return PaymentSlip[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $paymentSlips = $this->repository->findAll();
            return $paymentSlips;
        } catch (Exception $e) {
            $msg = "Error finding page of paymentSlips : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return PaymentSlip|null
     * @throws KerosException
     */
    public function getOne(int $id): ?PaymentSlip
    {
        try {
            $paymentSlip = $this->repository->find($id);
            return $paymentSlip;
        } catch (Exception $e) {
            $msg = "Error finding paymentSlip with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        try {
            $criteria = $requestParameters->getCriteria();
            $paymentSlips = $this->repository->matching($criteria)->getValues();
            return $paymentSlips;
        } catch (Exception $e) {
            $msg = "Error finding page of paymentSlips : " . $e->getMessage();
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

    /**
     * @param int $idMember
     * @throws KerosException
     */
    public function deletePaymentSlipsRelatedToMember(int $idMember)
    {
        $idMember = Validator::requiredId($idMember);
        $paymentSlips = $this->getAll();

        foreach ($paymentSlips as $paymentSlip) {
            $creator = $paymentSlip->getCreatedBy();
            if ($creator != null) {
                $creatorId = $creator->getId();
                if ($creatorId != null && $creatorId == $idMember)
                    $this->delete($paymentSlip);
            }

            $UAValidator = $paymentSlip->getValidatedByUaMember();
            $this->logger->info(json_encode($UAValidator));
            if ($UAValidator != null) {
                $UAValidatorId = $UAValidator->getId();
                if ($UAValidatorId != null && $UAValidatorId == $idMember)
                    $this->delete($paymentSlip);
            }

            $PerfValidator = $paymentSlip->getValidatedByPerfMember();
            if ($PerfValidator != null) {
                $PerfValidatorId = $PerfValidator->getId();
                if ($PerfValidatorId != null && $PerfValidatorId == $idMember)
                    $this->delete($paymentSlip);
            }
        }
    }
}