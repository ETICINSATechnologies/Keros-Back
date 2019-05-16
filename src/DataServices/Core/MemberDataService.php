<?php

namespace Keros\DataServices\Core;


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
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberDataService
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

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(Member::class);
        $this->queryBuilder = $this->entityManager->createQueryBuilder();
    }

    public function persist(Member $member): void
    {
        try {
            $this->entityManager->persist($member);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist member : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Member
    {
        try {
            $member = $this->repository->find($id);
            return $member;
        } catch (Exception $e) {
            $msg = "Error finding member with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @param $queryParams
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters, array $queryParams)
    {
        try {
            $this->queryBuilder
                ->select('m')
                ->from(Member::class, 'm')
                ->innerJoin(User::class, 'u', 'WITH', 'm.user = u')
                ->leftJoin(MemberPosition::class, 'mp', 'WITH', 'u.id = mp.member')
                ->leftJoin(Position::class, 'p', 'WITH', 'mp.position = p')
                ->leftJoin(Pole::class, 'pole', 'WITH', 'p.pole = pole.id');

            $whereStatement = '';
            $whereParameters = array();

            foreach ($queryParams as $key => $value) {
                if (in_array($key, ['search', 'poleId', 'positionId', 'year', 'firstName', 'lastName', 'company'])) {
                    if (!empty($whereStatement))
                        $whereStatement .= ' AND ';

                    if ($key == 'search') {
                        $searchValues = explode(' ', $value);
                        $searchStatement = '';
                        foreach ($searchValues as $i => $field) {
                            if (!empty($searchStatement))
                                $searchStatement .= ' AND ';

                            $searchStatement .=
                                '(m.firstName = :search' . $i
                                . ' OR m.lastName = :search' . $i
                                . ' OR m.company = :search' . $i . ')';
                            $whereParameters[':search' . $i] = $field;
                        }

                        $whereStatement .= $searchStatement;
                    } else {
                        if ($key == 'positionId') {
                            $whereStatement .= 'p.id = :positionId';
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'poleId') {
                            $whereStatement .= 'pole.id = :poleId';
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'year') {
                            $whereStatement .= 'mp.year = :year';
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'company') {
                            $whereStatement .= 'm.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'firstName' || $key == 'lastName') {
                            // where with the form: 'm.key = :key'
                            $whereStatement .= 'm.' . $key . ' LIKE :' . $key;
                            $whereParameters[':' . $key] = '%' . $value . '%';
                        }
                    }
                }
            }

            $order = $requestParameters->getParameters()['order'];
            $orderBy = $requestParameters->getParameters()['orderBy'];
            //$pageSize = $requestParameters->getParameters()['pageSize'];
            //$firstResult = $pageSize * $requestParameters->getParameters()['pageNumber'];

            if (!empty($whereStatement)) {
                $this->queryBuilder
                    ->where($whereStatement)
                    ->setParameters($whereParameters);
            }

            if (isset($orderBy)) {
                $this->queryBuilder->orderBy($orderBy, $order);
            }
/*
            $this->queryBuilder
                ->setFirstResult($firstResult)
                ->setMaxResults($pageSize);
*/
            $query = $this->queryBuilder->getQuery();//sql ok ici
    //        $paginator = new Paginator($query, $fetchJoinCollection = false);
      //      $this->logger->debug(count($paginator->getIterator()->getArrayCopy()));
  //          $page = new Page($query->execute(), $requestParameters, count($paginator));

            return $query->execute();

        } catch (Exception $e) {
            $msg = "Error finding page of members : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function delete(Member $member): void
    {
        try {
            $this->entityManager->remove($member);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete member : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}