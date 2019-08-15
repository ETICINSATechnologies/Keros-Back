<?php

namespace Keros\Services\Sg;

use DateTime;
use Keros\DataServices\Sg\MemberInscriptionDocumentDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Sg\MemberInscription;
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
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param MemberInscription $memberInscription
     * @param array $fields
     * @return MemberInscriptionDocument
     * @throws KerosException
     */
    public function create(MemberInscription $memberInscription, array $fields): MemberInscriptionDocument
    {
        $memberInscriptionId = Validator::requiredInt(intval($fields['id']));
        $documentTypeId = Validator::requiredInt(intval($fields['documentId']));
        if ($fields['file'] == null) {
            $msg = 'File is empty in given parameters';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        $file = $fields['file'];

        $memberInscriptionDocumentType = $this->memberInscriptionDocumentTypeService->getOne($documentTypeId);

        $date = new DateTime();
        $location = 'member_inscription_' . $memberInscriptionId . DIRECTORY_SEPARATOR . 'document_' . $documentTypeId . DIRECTORY_SEPARATOR;
        $location = $this->directoryManager->uniqueFilename($file, false, $location);

        $this->directoryManager->mkdir($this->kerosConfig['MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY'] . pathinfo($location, PATHINFO_DIRNAME));
        $document = new MemberInscriptionDocument($date, $location, $memberInscription, $memberInscriptionDocumentType, null);

        $this->memberInscriptionDocumentDataService->persist($document);

        return $document;
    }

    /**
     * @param int $id
     * @param MemberInscription|null $memberInscription
     * @param Member|null $member
     * @return MemberInscriptionDocument
     * @throws KerosException
     */
    public function update(int $id, ?MemberInscription $memberInscription, ?Member $member): MemberInscriptionDocument
    {
        $memberInscriptionDocumentId = Validator::requiredInt($id);

        $document = $this->memberInscriptionDocumentDataService->getOne($memberInscriptionDocumentId);
        $document->setMemberInscription($memberInscription);
        $document->setMember($member);

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

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id){
        $id = Validator::requiredId($id);
        $memberInscriptionDocument = $this->memberInscriptionDocumentDataService->getOne($id);
        unlink($this->directoryManager->normalizePath($this->kerosConfig["MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY"] . $memberInscriptionDocument->getLocation()));
        $this->memberInscriptionDocumentDataService->delete($memberInscriptionDocument);
    }

    /**
     * @return MemberInscriptionDocument[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->memberInscriptionDocumentDataService->getAll();
    }

    /**
     * @param int $documentTypeid
     * @param int $memberId
     * @return bool
     * @throws KerosException
     */
    public function documentTypeIsUploadedForMemberInscription(int $documentTypeid, int $memberId): bool
    {
        $memberInscriptionDocumentTypes = $this->getAll();
        foreach ($memberInscriptionDocumentTypes as $memberInscriptionDocumentType) {
            if ($memberInscriptionDocumentType->getId() == $documentTypeid && $memberInscriptionDocumentType->getMemberInscription()->getId() == $memberId)
                return true;
        }
        return false;
    }
}