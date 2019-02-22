<?php

namespace Keros\Controllers\Core;


use Doctrine\ORM\EntityManager;
use Keros\Error\KerosException;
use Keros\Services\Core\DocumentService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class DocumentController
{
    /**
     * @var DocumentService
     */
    private $documentService;

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
     * DocumentController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->documentService = $container->get(DocumentService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    public function createDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Uploading document " . $args['documentId'] . " for study " . $args['studyId'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        if ($request->getUploadedFiles() == null) {
            $msg = 'No file given ' . json_encode($request->getUploadedFiles());
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        $uploadedFile = $request->getUploadedFiles()['file'];
        if ($uploadedFile == null) {
            $msg = 'File is empty';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $msg = "Error during file uploading";
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
        $body = $args;
        $body['file'] = $uploadedFile->getClientFileName();

        $this->entityManager->beginTransaction();
        $document = $this->documentService->create($body);
        $this->logger->info($this->kerosConfig['STUDY_DOCUMENT_DIRECTORY'] . $document->getLocation());
        $uploadedFile->moveTo($this->kerosConfig['STUDY_DOCUMENT_DIRECTORY'] . $document->getLocation());
        $this->logger->info("bonjour");
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     */
    public function getDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting document path by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $document = $this->documentService->getLatestDocumentFromStudyTemplate($args["studyId"], $args['documentId']);

        return $response->withJson(array('location' => $this->kerosConfig['STUDY_DOCUMENT_DIRECTORY'] . $document->getLocation()), 200);
    }
}