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
use Keros\Tools\FileValidator;
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

        $uploadedFiles = FileValidator::requiredFiles($request->getUploadedFiles());

        $documentIdentity = FileValidator::requiredFileMixed($uploadedFiles['documentIdentity']);
        $documentScolaryCertificate = FileValidator::requiredFileMixed($uploadedFiles['documentScolaryCertificate']);
        $documentRIB = FileValidator::requiredFileMixed($uploadedFiles['documentRIB']);
        $documentVitaleCard = FileValidator::requiredFileMixed($uploadedFiles['documentVitaleCard']);
        $documentResidencePermit = FileValidator::optionalFileMixed($uploadedFiles['documentResidencePermit']);

        $documentIdentityFilename = $this->directoryManager->uniqueFilenameOnly($documentIdentity->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY']);
        $documentScolaryCertificateFilename = $this->directoryManager->uniqueFilenameOnly($documentScolaryCertificate->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY']);
        $documentRIBFilename = $this->directoryManager->uniqueFilenameOnly($documentRIB->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_RIB_DIRECTORY']);
        $documentVitaleCardFilename = $this->directoryManager->uniqueFilenameOnly($documentVitaleCard->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY']);
        $documentResidencePermitFilename = $documentResidencePermit ? $this->directoryManager->uniqueFilenameOnly($documentResidencePermit->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY']) : null;

        $documentIdentityFilepath = $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentIdentityFilename;
        $documentScolaryCertificateFilepath = $this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . $documentScolaryCertificateFilename;
        $documentRIBFilepath = $this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . $documentRIBFilename;
        $documentVitaleCardFilepath = $this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . $documentVitaleCardFilename;
        $documentResidencePermitFilepath = $documentResidencePermitFilename ? $this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . $documentResidencePermitFilename : null;

        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . pathinfo($documentIdentityFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . pathinfo($documentScolaryCertificateFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . pathinfo($documentRIBFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . pathinfo($documentVitaleCardFilename, PATHINFO_DIRNAME));
        if ($documentResidencePermit) $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . pathinfo($documentResidencePermitFilename, PATHINFO_DIRNAME));

        $body = $request->getParsedBody();
        $body['documentIdentity'] = $documentIdentityFilename;
        $body['documentScolaryCertificate'] = $documentScolaryCertificateFilename;
        $body['documentRIB'] = $documentRIBFilename;
        $body['documentVitaleCard'] = $documentVitaleCardFilename;
        if ($documentResidencePermit) $body['documentResidencePermit'] = $documentResidencePermitFilename;

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->create($body);
        $documentIdentity->moveTo($documentIdentityFilepath);
        $documentScolaryCertificate->moveTo($documentScolaryCertificateFilepath);
        $documentRIB->moveTo($documentRIBFilepath);
        $documentVitaleCard->moveTo($documentVitaleCardFilepath);
        if ($documentResidencePermit) $documentResidencePermit->moveTo($documentResidencePermitFilepath);
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

        $uploadedFiles = FileValidator::requiredFiles($request->getUploadedFiles());

        $documentIdentity = FileValidator::requiredFileMixed($uploadedFiles['documentIdentity']);
        $documentScolaryCertificate = FileValidator::requiredFileMixed($uploadedFiles['documentScolaryCertificate']);
        $documentRIB = FileValidator::requiredFileMixed($uploadedFiles['documentRIB']);
        $documentVitaleCard = FileValidator::requiredFileMixed($uploadedFiles['documentVitaleCard']);
        $documentResidencePermit = FileValidator::optionalFileMixed($uploadedFiles['documentResidencePermit']);

        $documentIdentityFilename = $this->directoryManager->uniqueFilenameOnly($documentIdentity->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY']);
        $documentScolaryCertificateFilename = $this->directoryManager->uniqueFilenameOnly($documentScolaryCertificate->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY']);
        $documentRIBFilename = $this->directoryManager->uniqueFilenameOnly($documentRIB->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_RIB_DIRECTORY']);
        $documentVitaleCardFilename = $this->directoryManager->uniqueFilenameOnly($documentVitaleCard->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY']);
        $documentResidencePermitFilename = $documentResidencePermit ? $this->directoryManager->uniqueFilenameOnly($documentResidencePermit->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY']) : null;

        $documentIdentityFilepath = $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentIdentityFilename;
        $documentScolaryCertificateFilepath = $this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . $documentScolaryCertificateFilename;
        $documentRIBFilepath = $this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . $documentRIBFilename;
        $documentVitaleCardFilepath = $this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . $documentVitaleCardFilename;
        $documentResidencePermitFilepath = $documentResidencePermitFilename ? $this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . $documentResidencePermitFilename : null;

        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . pathinfo($documentIdentityFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . pathinfo($documentScolaryCertificateFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . pathinfo($documentRIBFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . pathinfo($documentVitaleCardFilename, PATHINFO_DIRNAME));
        if ($documentResidencePermit) $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . pathinfo($documentResidencePermitFilename, PATHINFO_DIRNAME));

        $body = $request->getParsedBody();
        $body['documentIdentity'] = $documentIdentityFilename;
        $body['documentScolaryCertificate'] = $documentScolaryCertificateFilename;
        $body['documentRIB'] = $documentRIBFilename;
        $body['documentVitaleCard'] = $documentVitaleCardFilename;
        if ($documentResidencePermit) $body['documentResidencePermit'] = $documentResidencePermitFilename;

        $this->entityManager->beginTransaction();
        $consultantInscription = $this->consultantInscriptionService->update($args['id'], $body);
        $documentIdentity->moveTo($documentIdentityFilepath);
        $documentScolaryCertificate->moveTo($documentScolaryCertificateFilepath);
        $documentRIB->moveTo($documentRIBFilepath);
        $documentVitaleCard->moveTo($documentVitaleCardFilepath);
        if ($documentResidencePermit) $documentResidencePermit->moveTo($documentResidencePermitFilepath);
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
    public function getDocumentIdentity(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription documentIdentity from " . $request->getServerParams()["REMOTE_ADDR"]);
        $filepath = $this->consultantInscriptionService->getDocumentIdentity($args["id"]);
        $response = FileValidator::getFileResponse($filepath,$response);
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
    public function getDocumentScolaryCertificate(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription documentScolaryCertificate from " . $request->getServerParams()["REMOTE_ADDR"]);
        $filepath = $this->consultantInscriptionService->getDocumentScolaryCertificate($args["id"]);
        $response = FileValidator::getFileResponse($filepath);
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
    public function getDocumentRIB(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription documentRIB from " . $request->getServerParams()["REMOTE_ADDR"]);
        $filepath = $this->consultantInscriptionService->getDocumentRIB($args["id"]);
        $response = FileValidator::getFileResponse($filepath);
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
    public function getDocumentVitaleCard(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription documentVitaleCard from " . $request->getServerParams()["REMOTE_ADDR"]);
        $filepath = $this->consultantInscriptionService->getDocumentVitaleCard($args["id"]);
        $response = FileValidator::getFileResponse($filepath);
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
    public function getDocumentResidencePermit(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting consultantInscription documentResidencePermit from " . $request->getServerParams()["REMOTE_ADDR"]);
        $filepath = $this->consultantInscriptionService->getDocumentResidencePermit($args["id"]);
        $response = FileValidator::getFileResponse($filepath);
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
    public function createDocumentIdentity(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating consultantInscription documentIdentity from " . $request->getServerParams()["REMOTE_ADDR"]);
        $uploadedFiles = FileValidator::requiredFiles($request->getUploadedFiles());
        $uploadedFile = FileValidator::requiredFileMixed($uploadedFiles['documentIdentity']);
        $uploadedFileFilename = $this->directoryManager->uniqueFilenameOnly($uploadedFile->getClientFileName(), false, $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY']);
        $uploadedFileFilepath = $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $uploadedFileFilename;
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY']);

        $body = $request->getParsedBody();
        $body['documentIdentity'] = $uploadedFileFilename;

        $this->entityManager->beginTransaction();
        $this->consultantInscriptionService->createDocumentIdentity($args['id'], $body);
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