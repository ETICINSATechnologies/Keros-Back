<?php


namespace Keros\Controllers\Sg;


use Doctrine\ORM\EntityManager;
use Keros\Error\KerosException;
use Keros\Services\Sg\MemberInscriptionDocumentService;
use Keros\Services\Sg\MemberInscriptionDocumentTypeService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use \Exception;

class MemberInscriptionDocumentController
{

    /** @var Logger */
    private $logger;

    /** @var EntityManager */
    private $entityManager;

    /** @var ConfigLoader */
    private $kerosConfig;

    /** @var MemberInscriptionDocumentTypeService */
    private $memberInscriptionDocumentTypeService;

    /** @var MemberInscriptionDocumentService */
    private $memberInscriptionDocumentService;

    /** @var DirectoryManager */
    private $directoryManager;

    /**
     * MemberInscriptionDocumentController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->memberInscriptionDocumentTypeService = $container->get(MemberInscriptionDocumentTypeService::class);
        $this->memberInscriptionDocumentService = $container->get(MemberInscriptionDocumentService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function generateDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Generating document for memberInscription " . $args["id"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $location = $this->memberInscriptionDocumentTypeService->generateMemberInscriptionDocument($args["id"], $args["documentTypeId"]);
        $filename = pathinfo($location, PATHINFO_BASENAME);

        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . "/generated/" . $filename), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function createDocument(Request $request, Response $response, array $args){
        $this->logger->info("Uploading member inscription document " . $args["documentId"] . " for member inscription " . $args["id"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

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
            $msg = "Error during file uploading, error id " . $uploadedFile->getError() . ". Please see https://www.php.net/manual/en/features.file-upload.errors.php";
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }

        $body = $args;
        $body['file'] = $uploadedFile->getClientFileName();

        $this->entityManager->beginTransaction();
        $document = $this->memberInscriptionDocumentService->create($body);
        $uploadedFile->moveTo($this->directoryManager->normalizePath($this->kerosConfig['MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY'] . $document->getLocation()));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function getDocument(Request $request, Response $response, array $args){
        $this->logger->debug("Getting member inscription document path for member inscription " . $args["id"] . " and document type " . $args['documentId'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $document = $this->memberInscriptionDocumentService->getLatestDocumentFromMemberInscriptionDocumentType($args["id"], $args['documentId']);

        $location = $this->directoryManager->uniqueFilename($document->getLocation(), false, $this->kerosConfig['TEMPORARY_DIRECTORY']);
        $this->directoryManager->symlink($this->kerosConfig['MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY'] . $document->getLocation(), $location);

        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . DIRECTORY_SEPARATOR . '/generated/' . pathinfo($location, PATHINFO_BASENAME)), 200);
    }
}