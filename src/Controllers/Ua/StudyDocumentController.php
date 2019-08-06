<?php

namespace Keros\Controllers\Ua;


use Doctrine\ORM\EntityManager;
use Keros\Error\KerosException;
use Keros\Services\Ua\StudyDocumentService;
use Keros\Services\Ua\StudyDocumentTypeService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use \Exception;

class StudyDocumentController
{
    /**
     * @var StudyDocumentService
     */
    private $studyDocumentService;

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
     * @var StudyDocumentTypeService
     */
    private $studyDocumentTypeService;

    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /**
     * StudyDocumentController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->studyDocumentService = $container->get(StudyDocumentService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->studyDocumentTypeService = $container->get(StudyDocumentTypeService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function createDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Uploading document " . $args['documentId'] . " for study " . $args['studyId'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);
        if ($request->getUploadedFiles() == null) {
            $msg = 'No file given';
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
        $document = $this->studyDocumentService->create($body);
        $uploadedFile->moveTo($this->directoryManager->normalizePath($this->kerosConfig['STUDY_DOCUMENT_DIRECTORY'] . $document->getLocation()));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function getDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting document path for study " . $args["studyId"] . " and document type " . $args['documentId'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $document = $this->studyDocumentService->getLatestDocumentFromStudyDocumentType($args["studyId"], $args['documentId']);

        $location = $this->directoryManager->uniqueFilename($document->getLocation(), false, $this->kerosConfig['TEMPORARY_DIRECTORY']);
        $this->directoryManager->symlink($this->kerosConfig['STUDY_DOCUMENT_DIRECTORY'] . $document->getLocation(), $location);

        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . DIRECTORY_SEPARATOR . '/generated/' . pathinfo($location, PATHINFO_BASENAME)), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function generateStudyDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Generating document with document type " . $args["idDocumentType"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $location = $this->studyDocumentTypeService->generateStudyDocument($args["idDocumentType"], $args["idStudy"], $request->getAttribute("userId"));
        $filename = pathinfo($location, PATHINFO_BASENAME);

        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . "/generated/" . $filename), 200);
    }

}