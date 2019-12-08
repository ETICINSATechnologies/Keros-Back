<?php

namespace Keros\Controllers\Treso;


use Doctrine\ORM\EntityManager;
use Keros\Error\KerosException;
use Keros\Services\Treso\FactureDocumentService;
use Keros\Services\Treso\FactureDocumentTypeService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class FactureDocumentController
{
    /**
     * @var FactureDocumentService
     */
    private $factureDocumentService;

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
     * @var FactureDocumentTypeService
     */
    private $factureDocumentTypeService;

    /**
     * FactureDocumentController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->factureDocumentService = $container->get(FactureDocumentService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->factureDocumentTypeService = $container->get(FactureDocumentTypeService::class);
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
        $this->logger->debug("Uploading document " . $args['documentId'] . " for facture " . $args['factureId'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

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
        $document = $this->factureDocumentService->create($body);
        $uploadedFile->moveTo($this->kerosConfig['FACTURE_DOCUMENT_DIRECTORY'] . $document->getLocation());
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
        $this->logger->debug("Getting document path for facture " . $args["factureId"] . " and document type " . $args['documentId'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $document = $this->factureDocumentService->getLatestDocumentFromFactureDocumentType($args["factureId"], $args['documentId']);
        //Url de download non valide. Si jamais cette méthode est implémentée, il faudra la modifier (à la manière du download pour les study)
        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . DIRECTORY_SEPARATOR . $this->kerosConfig['FACTURE_DOCUMENT_DIRECTORY'] . $document->getLocation()), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    public function generateFactureDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Generating document for facture " . $args["idFacture"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $location = $this->factureDocumentTypeService->generateFactureDocument($args["idFacture"], $request->getAttribute("userId"));
        $filename = pathinfo($location, PATHINFO_BASENAME);

        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . "/generated/" . $filename), 200);
    }

}