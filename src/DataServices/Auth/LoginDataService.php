<?php

namespace Keros\DataServices\Auth;


use Keros\Entities\Core\User;
use Doctrine\ORM\EntityManager;
use Keros\Tools\Logger;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Error\KerosException;

class LoginDataService
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

    public function checkLogin(String $username, String $password): ?User
    {
        try {
            $criteria = [
                "username" => $username,
                "password" => $password
            ];
            $user = $this->repository->findBy($criteria)[0];
            return $user;
        } catch (Exception $e) {
            $msg = "Error logging user: " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}