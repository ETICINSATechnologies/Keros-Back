<?php

namespace Keros\DataServices\Sg;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Sg\MemberInscriptionDocumentType;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\DocumentGenerator;
use Monolog\Logger;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerInterface;

class MemberInscriptionDocumentTypeDataService
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $temporaryDirectory;

    /**
     * @var
     */
    protected $kerosConfig;

    /**
     * @var DocumentGenerator
     */
    protected $documentGenerator;

    /**
     * @var DirectoryManager
     */
    protected $directoryManager;

    /**
     * @var string
     */
    private $documentTypeDirectory;

    /**
     * MemberInscriptionDocumentTypeDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(MemberInscriptionDocumentType::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->documentGenerator = $container->get(DocumentGenerator::class);
        $this->documentTypeDirectory = $this->kerosConfig['DOCUMENT_TYPE_DIRECTORY'];
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param int $id
     * @return MemberInscriptionDocumentType|null
     * @throws KerosException
     */
    public function getOne(int $id): ?MemberInscriptionDocumentType
    {
        try {
            $documentType = $this->repository->find($id);
            return $documentType;
        } catch (Exception $e) {
            $msg = "Error finding document type with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

}