<?php

namespace Keros\Services\Sg;

use DateTime;
use Keros\DataServices\Sg\MemberInscriptionDocumentDataService;
use Keros\Entities\Sg\MemberInscriptionDocument;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberInscriptionDocumentService
{
    /** @var Logger */
    private $logger;

    /** @var MemberInscriptionDocumentDataService */
    private $memberInscriptionDocumentDataService;

    /** @var ConfigLoader */
    private $kerosConfig;

    /**  @var DirectoryManager */
    private $directoryManager;

    /** @var MemberInscriptionService */
    private $memberInscriptionService;

    /** @var MemberInscriptionDocumentTypeService */
    private $memberInscriptionDocumentTypeService;

    /**
     * MemberInscriptionDocumentService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->memberInscriptionDocumentDataService = $container->get(MemberInscriptionDocumentDataService::class);
        $this->memberInscriptionDocumentTypeService = $container->get(MemberInscriptionDocumentTypeService::class);
        $this->memberInscriptionService = $container->get(MemberInscriptionService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param array $fields
     * @return MemberInscriptionDocument
     * @throws KerosException
     */
    public function create(array $fields): MemberInscriptionDocument
    {
        $memberInscriptionId = Validator::requiredInt(intval($fields['id']));
        $documentTypeId = Validator::requiredInt(intval($fields['documentId']));
        if ($fields['file'] == null) {
            $msg = 'File is empty in given parameters';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        $file = $fields['file'];

        $memberInscription = $this->memberInscriptionService->getOne($memberInscriptionId);
    $memberInscriptionDocumentType = $this->memberInscriptionDocumentTypeService->getOne($documentTypeId);

        $date = new DateTime();
        $location = 'memberInscription_' . $memberInscriptionId . DIRECTORY_SEPARATOR . 'document_' . $documentTypeId . DIRECTORY_SEPARATOR;
        $location = $this->directoryManager->uniqueFilename($file, false, $location);

        $this->directoryManager->mkdir($this->kerosConfig['MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY'] . pathinfo($location, PATHINFO_DIRNAME));
        $document = new MemberInscriptionDocument($date, $location, $memberInscription, $memberInscriptionDocumentType);

        $this->memberInscriptionDocumentDataService->persist($document);

        return $document;
    }

    /**
     * @param int $memberInscriptionId
     * @param int $documentType
     * @return MemberInscriptionDocument
     * @throws KerosException
     */
    public function getLatestDocumentFromMemberInscriptionDocumentType(int $memberInscriptionId, int $documentType): MemberInscriptionDocument
    {
        $documents = $this->memberInscriptionDocumentDataService->getAll();

        $latestDocument = null;
        foreach ($documents as $document) {
            if ($document->getMemberInscription()->getId() == $memberInscriptionId && $document->getMemberInscriptionDocumentType()->getId() == $documentType)
                if ($latestDocument == null || $document->getUploadDate() > $latestDocument->getUploadDate())
                    $latestDocument = $document;
        }
        if ($latestDocument == null) {
            $msg = "No file found for member inscription " . $memberInscriptionId . " and document " . $documentType;
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        return $latestDocument;
    }
}