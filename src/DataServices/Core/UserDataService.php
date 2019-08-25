<?php

namespace Keros\DataServices\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Core\User;
use Keros\Error\KerosException;
use Keros\Tools\PasswordEncryption;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\QueryBuilder;

class UserDataService
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
        $this->repository = $this->entityManager->getRepository(User::class);
        $this->queryBuilder = $this->entityManager->createQueryBuilder();
    }

    public function persist(User $user)
    {
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist user : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getOne(int $id): ?User
    {
        try {
            $user = $this->repository->find($id);
            return $user;
        } catch (Exception $e) {
            $msg = "Error finding user with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try {
            $users = $this->repository->matching($criteria)->getValues();
            return $users;
        } catch (Exception $e) {
            $msg = "Error finding page of users : " . $e->getMessage();
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

    public function findByUsername(String $username): ?User
    {
        try {
            $criteria = [
                "username" => $username
            ];
            $users = $this->repository->findBy($criteria);

            // check if we get one and only one instance of user
            if (sizeof($users) == 1)
                return $users[0];
            return null;
        } catch (Exception $e) {
            $msg = "Error logging user: " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function delete(User $user)
    {
        try {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete user : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function doesUsernameExist(string $username): bool
    {
        try {

            $criteria = [
                "username" => $username
            ];
            $users = $this->repository->findBy($criteria);

            // check how many instances were found
            if (sizeof($users) > 0) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            $msg = "Failed to check username existence : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}