<?php

namespace Keros\DataServices\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\Pole;
use Keros\Entities\Core\Position;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\User;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class ConsultantDataService
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
        $this->repository = $this->entityManager->getRepository(Consultant::class);
        $this->queryBuilder = $this->entityManager->createQueryBuilder();
    }

    public function persist(Consultant $consultant): void
    {
        try {
            $this->entityManager->persist($consultant);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist consultant : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Consultant
    {
        try {
            $consultant = $this->repository->find($id);
            return $consultant;
        } catch (Exception $e) {
            $msg = "Error finding consultant with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @param $queryParams
     * @return Page
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters, array $queryParams)
    {
        try {
            $this->queryBuilder
                ->select('c')
                ->from(Consultant::class, 'c')
                ->innerJoin(User::class, 'u', 'WITH', 'c.user = u');

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
                                '(c.firstName = :search' . $i
                                . ' OR c.lastName = :search' . $i
                                . ' OR c.company = :search' . $i . ')';
                            $whereParameters[':search' . $i] = $field;
                        }

                        $whereStatement .= $searchStatement;
                    } else {
                        if ($key == 'company') {
                            $whereStatement .= 'c.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'firstName' || $key == 'lastName') {
                            // where with the form: 'c.key = :key'
                            $whereStatement .= 'c.' . $key . ' LIKE :' . $key;
                            $whereParameters[':' . $key] = '%' . $value . '%';
                        }
                    }
                }
            }

            $order = $requestParameters->getParameters()['order'];
            $orderBy = $requestParameters->getParameters()['orderBy'];
            $pageSize = $requestParameters->getParameters()['pageSize'];
            $firstResult = $pageSize * $requestParameters->getParameters()['pageNumber'];

            if (!empty($whereStatement)) {
                $this->queryBuilder
                    ->where($whereStatement)
                    ->setParameters($whereParameters);
            }

            if (isset($orderBy)) {
                $this->queryBuilder->orderBy($orderBy, $order);
            }

            $this->queryBuilder
                ->setFirstResult($firstResult)
                ->setMaxResults($pageSize);

            $query = $this->queryBuilder->getQuery();
            $paginator = new Paginator($query, $fetchJoinCollection = true);

            return new Page($query->execute(), $requestParameters, count($paginator));

        } catch (Exception $e) {
            $msg = "Error finding page of consultants : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function delete(Consultant $consultant): void
    {
        try {
            $this->entityManager->remove($consultant);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete consultant : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}