<?php


namespace Keros\DataServices\Sg;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Sg\MemberInscriptionDocument;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberInscriptionDocumentDataService
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
     * MemberInscriptionDocumentDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(MemberInscriptionDocument::class);
    }

    /**
     * @param int $id
     * @return MemberInscriptionDocument|null
     * @throws KerosException
     */
    public function getOne(int $id): ?MemberInscriptionDocument
    {
        try {
            $document = $this->repository->find($id);
            return $document;
        } catch (Exception $e) {
            $msg = "Error finding document with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

}