<?php

namespace Keros\Services\Treso;

use Keros\DataServices\Treso\FactureDocumentDataService;
use Keros\Entities\Treso\FactureDocument;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FactureDocumentService
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var FactureDocumentDataService
     */
    private $documentDataService;

    /**
     * @var FactureService
     */
    private $factureService;

    /**
     * @var FactureDocumentTypeService
     */
    private $factureDocumentTypeService;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /**
     * FactureDocumentService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->documentDataService = $container->get(FactureDocumentDataService::class);
        $this->factureDocumentTypeService = $container->get(FactureDocumentTypeService::class);
        $this->factureService = $container->get(FactureService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param array $fields
     * @return FactureDocument
     * @throws \Exception
     */
    public function create(array $fields): FactureDocument
    {
        $factureId = Validator::requiredInt(intval($fields['factureId']));
        $documentTypeId = Validator::requiredInt(intval($fields['documentId']));
        if ($fields['file'] == null) {
            $msg = 'File is empty in given parameters';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        $file = $fields['file'];

        $facture = $this->factureService->getOne($factureId);
        $factureDocumentType = $this->factureDocumentTypeService->getOne($documentTypeId);
        $date = new \DateTime();
        $location = 'facture_' . $factureId . DIRECTORY_SEPARATOR . 'document_' . $documentTypeId . DIRECTORY_SEPARATOR;
        $location = $this->directoryManager->uniqueFilename($file, false, $location);

        $this->directoryManager->mkdir($this->kerosConfig['FACTURE_DOCUMENT_DIRECTORY'] . pathinfo($location, PATHINFO_DIRNAME));
        $document = new FactureDocument($date, $location, $facture, $factureDocumentType);

        $this->documentDataService->persist($document);

        return $document;
    }

    /**
     * @param int $id
     * @return FactureDocument
     * @throws \Keros\Error\KerosException
     */
    public function getOne(int $id): FactureDocument
    {
        $id = Validator::requiredId($id);

        $document = $this->documentDataService->getOne($id);
        if (!$document) {
            throw new KerosException("The document could not be found", 404);
        }
        return $document;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id)
    {
        $id = Validator::requiredId($id);
        $document = $this->getOne($id);
        $this->documentDataService->delete($document);
    }

    /**
     * @param int $factureId
     * @param int $documentType
     * @return FactureDocument
     * @throws KerosException
     */
    public function getLatestDocumentFromFactureDocumentType(int $factureId, int $documentType): FactureDocument
    {
        $documents = $this->documentDataService->getAll();

        $latestDocument = null;
        foreach ($documents as $document) {
            if ($document->getFacture()->getId() == $factureId && $document->getFactureDocumentType()->getId() == $documentType)
                if ($latestDocument == null || $document->getUploadDate() > $latestDocument->getUploadDate())
                    $latestDocument = $document;
        }
        if ($latestDocument == null) {
            $msg = "No file found for facture " . $factureId . " and document " . $documentType;
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        return $latestDocument;
    }
}