<?php

namespace Keros\DataServices\Ua;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Contact;
use Keros\Entities\Ua\Firm;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class ContactDataService
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
        $this->repository = $this->entityManager->getRepository(Contact::class);
        $this->queryBuilder = $this->entityManager->createQueryBuilder();
    }

    public function delete(Contact $contact) : void
    {
        try {
            $this->entityManager->remove($contact);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete contact : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function persist(Contact $contact): void
    {
        try {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist contact : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getAll(): array
    {
        try {
            $contacts = $this->repository->findAll();
            return $contacts;
        } catch (Exception $e) {
            $msg = "Error finding page of contacts : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?Contact
    {
        try {
            $contact = $this->repository->find($id);
            return $contact;
        } catch (Exception $e) {
            $msg = "Error finding contact with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters, array $queryParams): Page
    {
        try {
            $this->queryBuilder
                ->select('c')
                ->from(Contact::class, 'c')
                ->leftJoin(Firm::class, 'f', 'WITH', 'c.firm = f');

            $whereStatement = '';
            $whereParameters = array();

            foreach ($queryParams as $key => $value) {
                if (in_array($key, ['search', 'firmId', 'firstName', 'lastName'])) {
                    if (!empty($whereStatement))
                        $whereStatement .= ' AND ';

                    if ($key == 'search') {
                        $searchValues = explode(' ', $value);
                        $searchStatement = '';
                        foreach ($searchValues as $i => $field) {
                            if (!empty($searchStatement))
                                $searchStatement .= ' AND ';

                            $searchStatement .=
                                '(c.firstName like :search' . $i
                                . ' OR c.lastName like :search' . $i . ')';
                            $whereParameters[':search' . $i] = '%' . $field . '%';
                        }

                        $whereStatement .= $searchStatement;
                    } else {
                        if ($key == 'firmId') {
                            $whereStatement .= 'f.id = :firmId';
                            $whereParameters[':' . $key] = $value;
                        }elseif ($key == 'firstName' || $key == 'lastName') {
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
            $msg = "Error finding page of contacts : " . $e->getMessage();
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
  
    public function getAllStudies(Contact $contact): array
    {
        $studies = [];
        foreach ($contact->getStudies() as $study)
        {
            $studies[] = $study;
        }

        return $studies;
    }
}