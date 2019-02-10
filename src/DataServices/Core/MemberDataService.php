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

    public function getAll(): array
    {
        try {
            $members = $this->repository->findAll();
            return $members;
        } catch (Exception $e) {
            $msg = "Error finding page of member : " . $e->getMessage();
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
     * @return array|Paginator|Member[]
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters, array $queryParams)
    {
        try {
            $this->queryBuilder
                ->select('m')
                ->from(Member::class, 'm')
                ->innerJoin(User::class, 'u', 'WITH', 'm.user = u')
                ->innerJoin(MemberPosition::class, 'mp', 'WITH', 'u.id = mp.member')
                ->innerJoin(Position::class, 'p', 'WITH', 'mp.position = p');

            $whereStatement = '';
            $whereParameters = array();

            foreach ($queryParams as $key => $value) {
                if (!empty($whereStatement))
                    $whereStatement .= ' AND ';

                if ($key == 'positionId')
                    $whereStatement .= 'p.id = :positionId';

                elseif ($key == 'year')
                    $whereStatement .= 'mp.year = :year';

                else
                    // where with the form: 'm.key = :key'
                    $whereStatement .= 'm.' . $key . ' = :' . $key;

                $whereParameters[':' . $key] = $value;
            }

            $this->logger->debug($whereStatement);
            $this->logger->debug(json_encode($whereParameters));

            $this->logger->debug(json_encode($requestParameters));

            $order = $requestParameters->getParameters()['order'];
            $orderBy = $requestParameters->getParameters()['orderBy'];
            $pageSize = $requestParameters->getParameters()['pageSize'];
            $firstResult = $pageSize * $requestParameters->getParameters()['pageNumber'];

            if (!empty($whereStatement)) {
                $this->queryBuilder
                    ->where($whereStatement)
                    ->setParameters($whereParameters);
            }

            if (isset($orderBy))
                $this->queryBuilder->orderBy($orderBy, $order);

            $this->queryBuilder
                ->setFirstResult($firstResult)
                ->setMaxResults($pageSize);

            $query = $this->queryBuilder->getQuery();

            $this->logger->debug($query->getDQL());
            $paginator = new Paginator($query, $fetchJoinCollection = true);

            return $query->execute();

        } catch (Exception $e) {
            $msg = "Error finding page of members : " . $e->getMessage();
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