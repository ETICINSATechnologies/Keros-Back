<?php

namespace Keros\Controllers\Sg;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Sg\ConsultantInscriptionService;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Keros\Tools\Helpers\FileHelper;
use Keros\Tools\Helpers\ConsultantInscriptionHelper;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;

class ConsultantInscriptionController
{
    /**
     * @var ConsultantInscriptionService
     */
    private $consultantInscriptionService;

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

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->consultantInscriptionService = $container->get(ConsultantInscriptionService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultantInscription = $this->consultantInscriptionService->getOne($args["id"]);

        return $response->withJson($consultantInscription, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getPageConsultantInscriptions(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page consultantInscriptions from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, ConsultantInscription::getSearchFields());

        $consultantInscriptions = $this->consultantInscriptionService->getPage($params);
        $count = $this->consultantInscriptionService->getCount($params);

        $page = new Page($consultantInscriptions, $params, $count);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function createConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $uploadedFiles = FileHelper::requiredFiles($request->getUploadedFiles());
        $consultantInscriptionFiles = ConsultantInscriptionHelper::getConsultantInscriptionFiles();

        $body = $request->getParsedBody();

        $file_array = array();

        foreach ($consultantInscriptionFiles as $consultantInscriptionFile) {
            //get validator function name
            $validatorFunction = $consultantInscriptionFile['validator'];
            //get file
            if (array_key_exists($consultantInscriptionFile['name'], $uploadedFiles)) {
                $document = FileHelper::$validatorFunction($uploadedFiles[$consultantInscriptionFile['name']]);
            } else {
                $document = null;
            }
            //get filename
            $documentFilename = $document ? $this->directoryManager->uniqueFilenameOnly($document->getClientFileName(), false, $this->kerosConfig[$consultantInscriptionFile['directory_key']]) : null;
            //get filepath
            $documentFilepath = $document ? $this->kerosConfig[$consultantInscriptionFile['directory_key']] . $documentFilename : null;
            //make directory and store filename/filepath
            if ($document) {
                $this->directoryManager->mkdir($this->kerosConfig[$consultantInscriptionFile['directory_key']]);
                array_push(
                    $file_array,
                    array(
                        'file' => $document,
                        'filepath' => $documentFilepath,
                    )
                );
            }
            //add to body
            $body[$consultantInscriptionFile['name']] = $documentFilename;
        }

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->create($body);

        foreach ($file_array as $file) {
            $file['file']->moveTo($file['filepath']);
        }

        $this->entityManager->commit();

        return $response->withJson($consultantInscription, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function updateConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($consultantInscription, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function deleteConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->consultantInscriptionService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
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
        $document_name = ConsultantInscriptionHelper::doesExist($args['document_name']);
        $this->logger->debug("Getting consultantInscription $document_name from " . $request->getServerParams()["REMOTE_ADDR"]);
        $filepath = $this->consultantInscriptionService->getDocument($args["id"], $document_name);
        $response = FileHelper::getFileResponse($filepath, $response);
        readfile($filepath);
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function createDocument(Request $request, Response $response, array $args)
    {
        $document_name = ConsultantInscriptionHelper::doesExist($args['document_name']);
        $this->logger->debug("Creating consultantInscription $document_name from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultantInscriptionFile = ConsultantInscriptionHelper::getConsultantInscriptionFiles()[$document_name];

        $uploadedFiles = FileHelper::requiredFiles($request->getUploadedFiles());
        $uploadedFile = FileHelper::requiredFileMixed(reset($uploadedFiles));
        $uploadedFileFilename = $this->directoryManager->uniqueFilenameOnly($uploadedFile->getClientFileName(), false, $this->kerosConfig[$consultantInscriptionFile['directory_key']]);
        $uploadedFileFilepath = $this->kerosConfig[$consultantInscriptionFile['directory_key']] . $uploadedFileFilename;

        $this->directoryManager->mkdir($this->kerosConfig[$consultantInscriptionFile['directory_key']]);

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->createDocument($args['id'], $document_name, $uploadedFileFilename);
        $uploadedFile->moveTo($uploadedFileFilepath);
        $this->entityManager->commit();

        return $response->withJson($consultantInscription, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function validateConsultantInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating consultantInscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->consultantInscriptionService->validateConsultantInscription($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }
}
