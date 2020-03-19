<?php

namespace Keros\DataServices\Sg;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\MemberPosition;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\Pole;
use Keros\Entities\Core\Position;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\User;
use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class   MemberInscriptionDataService
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
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * MemberInscriptionDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(MemberInscription::class);
        $this->queryBuilder = $this->entityManager->createQueryBuilder();
    }

    /**
     * @param MemberInscription $memberInscription
     * @throws KerosException
     */
    public function persist(MemberInscription $memberInscription): void
    {
        try {
            $this->entityManager->persist($memberInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to persist   MemberInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param MemberInscription $memberInscription
     * @throws KerosException
     */
    public function delete(MemberInscription $memberInscription): void
    {
        try {
            $this->entityManager->remove($memberInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to delete   MemberInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return MemberInscription[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $memberInscriptions = $this->repository->findAll();
            return $memberInscriptions;
        } catch (Exception $e) {
            $msg = "Error finding page of memberInscriptions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return MemberInscription|null
     * @throws KerosException
     */
    public function getOne(int $id): ?MemberInscription
    {
        try {
            $memberInscription = $this->repository->find($id);
            return $memberInscription;
        } catch (Exception $e) {
            $msg = "Error finding member_inscription with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters,  array $queryParams): Page
    {
        try {
            $this->queryBuilder = $this->entityManager->createQueryBuilder();
            $this->queryBuilder
                ->select('mi')
                ->from(MemberInscription::class, 'mi')
                ->groupBy('mi.id');

            $whereStatement = '';
            $whereParameters = array();

            foreach ($queryParams as $key => $value) {
                if (in_array($key, ['search', 'createdDate', 'email', 'phoneNumber', 'outYear', 'firstName', 'hasPaid', 'lastName', 'company', 'isAlumni'])) {
                    if (!empty($whereStatement))
                        $whereStatement .= ' AND ';

                    if ($key == 'search') {
                        $searchValues = explode(' ', $value);
                        $searchStatement = '';
                        foreach ($searchValues as $i => $field) {
                            if (!empty($searchStatement))
                                $searchStatement .= ' AND ';

                            $searchStatement .=
                                '(mi.firstName like :search' . $i
                                . ' OR mi.lastName like :search' . $i . ')';
                            $whereParameters[':search' . $i] = '%' . $field . '%';
                        }

                        $whereStatement .= $searchStatement;
                    } else {
                        if ($key == 'createdDate') {
                            $whereStatement .= 'mi.createdDate >= :createdDate';
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'email') {
                            $whereStatement .= 'mi.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'outYear') {
                            $whereStatement .= 'mi.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'hasPaid') {
                            $whereStatement .= 'mi.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'phoneNumber') {
                            $whereStatement .= 'mi.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'company') {
                            $whereStatement .= 'mi.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'firstName' || $key == 'lastName') {
                            // where with the form: 'mi.key = :key'
                            $whereStatement .= 'mi.' . $key . ' LIKE :' . $key;
                            $whereParameters[':' . $key] = '%' . $value . '%';
                        } elseif ($key == 'isAlumni') {
                            $booleanValue = filter_var(strtolower($value), FILTER_VALIDATE_BOOLEAN);
                            $whereStatement .= 'mi.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $booleanValue;
                        }
                    }

                    /* $whereStatement .=*/
                }
            }

            if (!empty($whereStatement)) {
                $this->queryBuilder
                    ->where($whereStatement)
                    ->setParameters($whereParameters);
            }

            $order = $requestParameters->getParameters()['order'];
            $orderBy = $requestParameters->getParameters()['orderBy'];
            $pageSize = $requestParameters->getParameters()['pageSize'];
            $firstResult = $pageSize * $requestParameters->getParameters()['pageNumber'];

            if (isset($orderBy)) {
                switch ($orderBy) {
                    case 'lastName' :
                    case 'firstName' :
                    case 'email' :
                        $this->queryBuilder->orderBy("mi.$orderBy", $order);
                        break;
                }
            }

            $this->queryBuilder
                ->setFirstResult($firstResult)
                ->setMaxResults($pageSize);
            $query = $this->queryBuilder->getQuery();
            $paginator = new Paginator($query, $fetchJoinCollection = true);
            return new Page($query->execute(), $requestParameters, count($paginator));

        } catch (Exception $e) {
            $msg = "Error finding page of members : " . $e->getMessage();
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

}