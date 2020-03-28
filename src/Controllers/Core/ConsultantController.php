<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Auth\AccessRightsService;
use Keros\Services\Core\ConsultantService;
use Keros\Tools\Authorization\JwtCodec;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Keros\Tools\Helpers\FileHelper;
use Keros\Tools\FileValidator;
use Keros\Tools\Helpers\ConsultantFileHelper;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;


class ConsultantController
{
    /**
     * @var ConsultantService
     */
    private $consultantService;

    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var JwtCodec
     */
    private $jwtCodec;
    /**
     * @var ConfigLoader
     */
    private $kerosConfig;
    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /** @var AccessRightsService  */
    private $accessRightsService;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->consultantService = $container->get(ConsultantService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->accessRightsService = $container->get(AccessRightsService::class);
    }

    public function getConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultant by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultant = $this->consultantService->getOne($args["id"]);

        return $response->withJson($consultant, 200);
    }

    public function getConnectedConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting connected user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultant = $this->consultantService->getOne($request->getAttribute("userId"));

        return $response->withJson($consultant, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getConsultantProtectedData(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultant protected data by ID " . $args["id"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->accessRightsService->ensureOnlyGeneralSecretary($request);

        $consultantProtectedData = $this->consultantService->getOneProtectedData($args["id"]);

        return $response->withJson($consultantProtectedData, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getConnectedConsultantProtectedData(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting connected consultant protected data by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $consultantProtectedData = $this->consultantService->getOneProtectedData($request->getAttribute("userId"));

        return $response->withJson($consultantProtectedData, 200);
    }

    public function updateConnectedConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting updated user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->update($request->getAttribute("userId"), $body);
        $this->entityManager->commit();

        return $response->withJson($consultant, 200);
    }

    public function getPageConsultants(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page consultants from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $requestParameters = new RequestParameters($queryParams, Consultant::getSearchFields());

        $page = $this->consultantService->getPage($requestParameters, $queryParams);

        return $response->withJson($page, 200);
    }

    public function createConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating consultant from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->accessRightsService->ensureOnlyGeneralSecretary();
        $uploadedFiles = FileValidator::optionalFiles($request->getUploadedFiles());
        $consultantFiles = ConsultantFileHelper::getConsultantFiles();
        $body = $request->getParsedBody();

        $file_array = array();

        foreach ($consultantFiles as $consultantFile) {
            $file_details = $this->consultantService->getFileDetailsFromUploadedFiles($uploadedFiles,$consultantFile);
            if($file_details) {
                $body[$consultantFile['name']] = $file_details['filename'];
                array_push($file_array,$file_details);
            }
        }

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->create($body);

        foreach ($file_array as $file) {
            $file['file']->moveTo($file['filepath']);
        }

        $this->entityManager->commit();

        return $response->withJson($consultant, 201);
    }

    public function updateConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating consultant from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($consultant, 200);
    }

    public function deleteConsultant(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting consultant from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->consultantService->delete($args['id']);
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
        $this->logger->debug("Getting consultant document from " . $request->getServerParams()["REMOTE_ADDR"]);

        $document_name = ConsultantFileHelper::doesExist($args['document_name']);
        $filepath = $this->consultantService->getDocument($args["id"], $document_name);
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
        $this->logger->debug("Creating consultant document from " . $request->getServerParams()["REMOTE_ADDR"]);

        $document_name = ConsultantFileHelper::doesExist($args['document_name']);
        $consultantFile = ConsultantFileHelper::getConsultantFiles()[$document_name];

        $uploadedFiles = FileValidator::requiredFiles($request->getUploadedFiles());
        $uploadedFile = FileValidator::requiredFileMixed(reset($uploadedFiles));
        $uploadedFileFilename = $this->directoryManager->uniqueFilenameOnly($uploadedFile->getClientFileName(), false, $this->kerosConfig[$consultantFile['directory_key']]);
        $uploadedFileFilepath = $this->kerosConfig[$consultantFile['directory_key']] . $uploadedFileFilename;

        $this->directoryManager->mkdir($this->kerosConfig[$consultantFile['directory_key']]);

        $this->entityManager->beginTransaction();
        $consultant = $this->consultantService->createDocument($args['id'], $document_name, $uploadedFileFilename);
        $uploadedFile->moveTo($uploadedFileFilepath);
        $this->entityManager->commit();

        return $response->withJson($consultant, 201);
	}

	public function exportConsultants(Request $request, Response $response, array $args)
	{
		$this->logger->debug("Exporting specified consultants to csv file.");
		$body = $request->getParsedBody();
		$location = $this->consultantService->export($body['idList']);
		return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . "/generated/" . $location), 200);
	}
}
