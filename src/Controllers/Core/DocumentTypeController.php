<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Services\Treso\FactureDocumentTypeService;
use Keros\Services\Ua\StudyDocumentTypeService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class DocumentTypeController
{

    /**
     * @var StudyDocumentTypeService
     */
    private $studyDocumentTypeService;


    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /**
     * @var string
     */
    private $studyDocumentTypeDirectory;


    /**
     * DocumentTypeController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->studyDocumentTypeService = $container->get(FactureDocumentTypeService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->studyDocumentTypeDirectory = $this->kerosConfig['DOCUMENT_TYPE_DIRECTORY'];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     */
    public function getDocumentType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting document type by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $documentType = $this->studyDocumentTypeService->getOne($args['id']);

        return $response->withJson($documentType, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     */
    public function createDocumentType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating document type from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $uploadedFile = $request->getUploadedFiles()['file'];
        $body["extension"] = pathinfo($uploadedFile->getClientFileName(), PATHINFO_EXTENSION);

        $this->entityManager->beginTransaction();
        $documentType = $this->studyDocumentTypeService->create($body);
        $location = $this->studyDocumentTypeDirectory . $documentType->getLocation();
        $this->directoryManager->mkdir(pathinfo($location, PATHINFO_DIRNAME));
        $uploadedFile->moveTo($location);
        $this->entityManager->commit();

        return $response->withJson($documentType, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Keros\Error\KerosException
     */
    public function deleteDocumentType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting document type from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->entityManager->beginTransaction();
        $this->studyDocumentTypeService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     */
    public function getAllDocumentType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all Documents Types from " . $request->getServerParams()["REMOTE_ADDR"]);

        $documentType = $this->studyDocumentTypeService->getAll();

        return $response->withJson($documentType, 200);
    }


}