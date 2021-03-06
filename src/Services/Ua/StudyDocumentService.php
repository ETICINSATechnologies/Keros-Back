<?php

namespace Keros\Services\Ua;

use Keros\DataServices\Ua\StudyDocumentDataService;
use Keros\Entities\Ua\StudyDocument;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class StudyDocumentService
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var StudyDocumentDataService
     */
    private $studyDocumentDataService;

    /**
     * @var StudyService
     */
    private $studyService;

    /**
     * @var StudyDocumentTypeService
     */
    private $studyDocumentTypeService;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /**
     * StudyDocumentService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->studyDocumentDataService = $container->get(StudyDocumentDataService::class);
        $this->studyDocumentTypeService = $container->get(StudyDocumentTypeService::class);
        $this->studyService = $container->get(StudyService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param array $fields
     * @return StudyDocument
     * @throws \Exception
     */
    public function create(array $fields): StudyDocument
    {
        $studyId = Validator::requiredInt(intval($fields['studyId']));
        $documentTypeId = Validator::requiredInt(intval($fields['documentId']));
        if ($fields['file'] == null) {
            $msg = 'File is empty in given parameters';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        $file = $fields['file'];

        $study = $this->studyService->getOne($studyId);
        $studyDocumentType = $this->studyDocumentTypeService->getOne($documentTypeId);

        $date = new \DateTime();
        $location = 'study_' . $studyId . DIRECTORY_SEPARATOR . 'document_' . $documentTypeId . DIRECTORY_SEPARATOR;
        $location = $this->directoryManager->uniqueFilename($file, false, $location);

        $this->directoryManager->mkdir($this->kerosConfig['STUDY_DOCUMENT_DIRECTORY'] . pathinfo($location, PATHINFO_DIRNAME));
        $document = new StudyDocument($date, $location, $study, $studyDocumentType);

        $this->studyDocumentDataService->persist($document);

        return $document;
    }

    /**
     * @param int $id
     * @return StudyDocument
     * @throws \Keros\Error\KerosException
     */
    public function getOne(int $id): StudyDocument
    {
        $id = Validator::requiredId($id);

        $document = $this->studyDocumentDataService->getOne($id);
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
        $this->studyDocumentDataService->delete($document);
    }

    /**
     * @param int $studyId
     * @param int $documentType
     * @return StudyDocument
     * @throws KerosException
     */
    public function getLatestDocumentFromStudyDocumentType(int $studyId, int $documentType): StudyDocument
    {
        $documents = $this->studyDocumentDataService->getAll();

        $latestDocument = null;
        foreach ($documents as $document) {
            if ($document->getStudy()->getId() == $studyId && $document->getStudyDocumentType()->getId() == $documentType)
                if ($latestDocument == null || $document->getUploadDate() > $latestDocument->getUploadDate())
                    $latestDocument = $document;
        }
        if ($latestDocument == null) {
            $msg = "No file found for study " . $studyId . " and document " . $documentType;
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        return $latestDocument;
    }

    /**
     * @return StudyDocument[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->studyDocumentDataService->getAll();
    }

    /**
     * @param int $documentTypeId
     * @param int $studyId
     * @return bool
     * @throws KerosException
     */
    public function documentTypeIsUploadedForStudy(int $documentTypeId, int $studyId): bool
    {
        $studyDocumentTypes = $this->getAll();
        foreach ($studyDocumentTypes as $studyDocumentType) {
            if ($studyDocumentType->getId() == $documentTypeId && $studyDocumentType->getStudy()->getId() == $studyId)
                return true;
        }
        return false;
    }
}