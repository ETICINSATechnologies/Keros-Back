<?php

namespace Keros\Services\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\User;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class UserService
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
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @param User $user
     * @throws KerosException
     */
    public function create(User $user)
    {
        $this->entityManager->beginTransaction();
        try
        {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e)
        {
            $msg = "Failed to create new user : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return User|null
     * @throws KerosException
     */
    public function getOne(int $id): ?User
    {
        try
        {
            $user = $this->repository->find($id);
            return $user;
        } catch (Exception $e)
        {
            $msg = "Error finding user with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getMany(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try
        {
            $users = $this->repository->matching($criteria)->getValues();
            return $users;
        } catch (Exception $e)
        {
            $msg = "Error finding page of users : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getCount(?RequestParameters $requestParameters): int
    {
        if ($requestParameters != null)
        {
            $countCriteria = $requestParameters->getCountCriteria();
            $count = $this->repository->matching($countCriteria)->count();
        }
        else
        {
            $count = $this->repository->matching(Criteria::create())->count();
        }
        return $count;
    }

    /**
     * @param $userId
     * @param $username
     * @param $password
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     * @throws KerosException
     */
    function update($userId, $username, $password)
    {
        $this->entityManager->beginTransaction();
        try {
            $user = $this->entityManager->getReference('Keros\Entities\Core\User', $userId);

            $user->setUsername($username);
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $user;
        } catch (Exception $e) {
            $msg = "Failed to update user : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}