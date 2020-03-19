<?php

namespace Keros\DataServices\Sg;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class   ConsultantInscriptionDataService
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
     * ConsultantInscriptionDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(ConsultantInscription::class);
    }

    /**
     * @param ConsultantInscription $consultantInscription
     * @throws KerosException
     */
    public function persist(ConsultantInscription $consultantInscription): void
    {
        try {
            $this->entityManager->persist($consultantInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to persist ConsultantInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param ConsultantInscription $consultantInscription
     * @throws KerosException
     */
    public function delete(ConsultantInscription $consultantInscription): void
    {
        try {
            $this->entityManager->remove($consultantInscription);
            $this->entityManager->flush();
        } catch
        (Exception $e) {
            $msg = "Failed to delete ConsultantInscription : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return ConsultantInscription[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $consultantInscriptions = $this->repository->findAll();
            return $consultantInscriptions;
        } catch (Exception $e) {
            $msg = "Error finding page of consultantInscriptions : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return ConsultantInscription|null
     * @throws KerosException
     */
    public function getOne(int $id): ?ConsultantInscription
    {
        try {
            $consultantInscription = $this->repository->find($id);
            return $consultantInscription;
        } catch (Exception $e) {
            $msg = "Error finding consultantInscription with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters, array $queryParams): Page
    {
        try {
            $this->queryBuilder = $this->entityManager->createQueryBuilder();
            $this->queryBuilder
                ->select('ci')
                ->from(ConsultantInscription::class, 'ci')
                ->groupBy('ci.id');

            $whereStatement = '';
            $whereParameters = array();

            foreach ($queryParams as $key => $value) {
                if (in_array($key, ['search', 'createdDate', 'firstName', 'lastName', 'company', 'isAlumni'])) {
                    if (!empty($whereStatement))
                        $whereStatement .= ' AND ';

                    if ($key == 'search') {
                        $searchValues = explode(' ', $value);
                        $searchStatement = '';
                        foreach ($searchValues as $i => $field) {
                            if (!empty($searchStatement))
                                $searchStatement .= ' AND ';

                            $searchStatement .=
                                '(ci.firstName like :search' . $i
                                . ' OR ci.lastName like :search' . $i
                                . ' OR ci.company like :search' . $i . ')';
                            $whereParameters[':search' . $i] = '%' . $field . '%';
                        }

                        $whereStatement .= $searchStatement;
                    } else {
                        if ($key == 'createdDate') {
                            $whereStatement .= 'ci.createdDate >= :createdDate';
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'company') {
                            $whereStatement .= 'ci.' . $key . ' = :' . $key;
                            $whereParameters[':' . $key] = $value;
                        } elseif ($key == 'firstName' || $key == 'lastName') {
                            // where with the form: 'ci.key = :key'
                            $whereStatement .= 'ci.' . $key . ' LIKE :' . $key;
                            $whereParameters[':' . $key] = '%' . $value . '%';
                        } elseif ($key == 'isAlumni') {
                            $booleanValue = filter_var(strtolower($value), FILTER_VALIDATE_BOOLEAN);
                            $whereStatement .= 'ci.' . $key . ' = :' . $key;
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
                        $this->queryBuilder->orderBy("ci.$orderBy", $order);
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