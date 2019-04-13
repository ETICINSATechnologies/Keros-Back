<?php

namespace Keros\Services\Ua;


use Keros\DataServices\Ua\StudyDocumentTypeDataService;
use Keros\Entities\Ua\StudyDocumentType;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\GenderBuilder;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Entities\Ua\Study;
use Keros\Entities\Core\Member;


class StudyDocumentTypeService
{

    /**
     * @var StudyService
     */
    private $studyService;

    /**
     * @var GenderBuilder
     */
    private $genderBuilder;

    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var
     */
    private $kerosConfig;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    private $documentTypeDirectory;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * @var StudyDocumentTypeDataService
     */
    protected $studyDocumentTypeDataService;

    /**
     * @var DirectoryManager
     */
    protected $directoryManager;

    public function __construct(ContainerInterface $container)
    {
        $this->studyService = $container->get(StudyService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->genderBuilder = $container->get(GenderBuilder::class);
        $this->logger = $container->get(Logger::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->documentTypeDirectory = $this->kerosConfig['DOCUMENT_TYPE_DIRECTORY'];
        $this->studyDocumentTypeDataService = $container->get(StudyDocumentTypeDataService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
    }


    /**
     * @param array $fields
     * @return StudyDocumentType
     * @throws KerosException
     * @throws \Exception
     */
    public function create(array $fields): StudyDocumentType
    {
        $oneConsultant = Validator::requiredBool(boolval($fields["oneConsultant"]));
        $isTemplatable = Validator::requiredBool(boolval($fields["isTemplatable"]));
        $extension = Validator::requiredString($fields["extension"]);

        $date = new \DateTime();
        $location = $date->format('d-m-Y_H:i:s:u') . '.' . $extension;
        $documentType = new StudyDocumentType($location, $isTemplatable, $oneConsultant);

        $this->studyDocumentTypeDataService->persist($documentType);

        return $documentType;
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Keros\Error\KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $documentType = $this->getOne($id);
        $this->studyDocumentTypeDataService->delete($documentType);
    }

    /**
     * @param int $id
     * @return StudyDocumentType
     * @throws \Keros\Error\KerosException
     */
    public function getOne(int $id): StudyDocumentType
    {
        $id = Validator::requiredId($id);

        $documentType = $this->studyDocumentTypeDataService->getOne($id);
        if (!$documentType) {
            throw new KerosException("The studyDocumentType could not be found", 404);
        }
        return $documentType;
    }

    /**
     * @return StudyDocumentType[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->studyDocumentTypeDataService->getAll();
    }

    /**
     * @param int $documentTypeId
     * @param int $studyId
     * @param int $connectedUserId
     * @return string
     * @throws \Exception
     */
    public function generateStudyDocument(int $documentTypeId, int $studyId, int $connectedUserId): string
    {
        $study = $this->studyService->getOne($studyId);
        $documentType = $this->getOne($documentTypeId);
        $connectedUser = $this->memberService->getOne($connectedUserId);

        if (!$documentType->getisTemplatable()) {
            $msg = "Document type " . $documentType->getId() . " is not templatable";
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        if ($study->getContactsArray() == null || empty($study->getContactsArray())) {
            $msg = "No contact in study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        if (!$this->studyService->consultantAreValid($study->getId())) {
            $msg = "Invalid consultant in study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        $this->directoryManager->mkdir($this->kerosConfig["TEMPORARY_DIRECTORY"]);
        //Zip are done if one document per consultant is needed
        $doZip = $documentType->getOneConsultant() == 1;
        $searchArray = $this->getStudySearchArray();

        if ($doZip) {
            $replacementArrays = array();
            foreach ($study->getConsultantsArray() as $consultant)
                $replacementArrays[] = $this->getStudyReplacementArray($study, $connectedUser, array($consultant));
            $location = $this->studyDocumentTypeDataService->generateMultipleDocument($documentType, $searchArray, $replacementArrays);
        } else {
            $replacementArray = $this->getStudyReplacementArray($study, $connectedUser, $study->getConsultantsArray());
            $location = $this->studyDocumentTypeDataService->generateSimpleDocument($documentType, $searchArray, $replacementArray);
        }
        return $location;
    }

    /**
     * To add pattern, add here and in :getReplacementArray AT THE SAME INDEX
     * @return array
     */
    public function getStudySearchArray(): array
    {
        return array(
            '${NOMENTREPRISE}',
            '${TITREETUDE}',
            '${ADRESSEENTREPRISE}',
            '${CPENTREPRISE}',
            '${VILLEENTREPRISE}',
            '${SIRETENTREPRISE}',
            '${DESCRIPTIONETUDE}',
            '${DATESIGCV}',
            '${FCTCONTACT}',
            '${CIVILITECONTACT}',
            '${PRENOMCONTACT}',
            '${NOMCONTACT}',
            '${MAILCONTACT}',
            '${DJOUR}',
            '${NUMINTERVENANT}',
            '${CIVILITEINTERVENANT}',
            '${PRENOMINTERVENANT}',
            '${NOMINTERVENANT}',
            '${MAILINTERVENANT}',
            '${ADRESSEINTERVENANT}',
            '${CPINTERVENANT}',
            '${VILLEINTERVENANT}',
            '${NOMUSER}',
            '${PRENOMUSER}',
            '${CIVILITEUSER}',
            '${IDENTITECONTACT}',
            '${IDENTITEINTERVENANT}',
            '${INDENTITEUSER}',
            '${DATEFIN}',
            '${NOMPRESIDENT}',
            '${CIVPRESIDENT}',
            '${PRENOMPRESIDENT}',
            '${NOMTRESORIER}',
            '${CIVTRESORIER}',
            '${PRENOMTRESORIER}',
            '${IDENTITETRESORIER}',
            '${IDENTITEPRESIDENT}'
        );
    }

    /**
     * To add replacement, add here and in :getSearchArray AT THE SAME INDEX
     * @param Study $study
     * @param Member $connectedUser
     * @param Member[] $consultants
     * @return array
     * @throws \Exception
     */
    public function getStudyReplacementArray(Study $study, Member $connectedUser, array $consultants): array
    {
        $contact = $study->getContacts()[0];
        $date = new \DateTime();

        $consultantsIdentity = '';
        $nbConsultant = 0;
        //loop to have multiple consultant identity correctly
        foreach ($study->getConsultantsArray() as $consultant) {
            $consultantsIdentity .= $this->genderBuilder->getStringGender($consultant->getGender()) . ' ' . $consultant->getLastName() . ' ' . $consultant->getFirstName();
            //If we are not one the last consultant in the array
            if (++$nbConsultant !== count($study->getConsultantsArray()))
                $consultantsIdentity .= ', ';
        }

        //Information about actual board
        $tresorier = null;
        $president = null;
        $board = $this->memberService->getLatestBoard();
        foreach ($board as $member) {
            foreach ($member->getPositionsArray() as $position) {
                if ($position->getIsBoard()) {
                    if ($position->getPosition()->getId() == 23)
                        $tresorier = $position->getMember();
                    else if ($position->getPosition()->getId() == 14)
                        $president = $position->getMember();
                }
            }
        }

        return array(
            $study->getFirm()->getName(),
            $study->getName(),
            $study->getFirm()->getAddress()->getLine1() . ", " . (($study->getFirm()->getAddress()->getLine2() != null) ? ", " . $study->getFirm()->getAddress()->getLine2() : ""),
            $study->getFirm()->getAddress()->getPostalCode(),
            $study->getFirm()->getAddress()->getCity(),
            ($study->getFirm()->getSiret() != null) ? $study->getFirm()->getSiret() : '${SIRETENTREPRISE}',
            ($study->getDescription() != null) ? $study->getDescription() : '${DESCRIPTIONETUDE}',
            ($study->getSignDate() != null) ? $study->getSignDate()->format('d/m/Y') : '${DATESIGCV}',
            ($contact->getPosition() != null) ? $contact->getPosition() : '${FCTCONTACT}',
            $this->genderBuilder->getStringGender($contact->getGender()),
            $contact->getFirstName(),
            $contact->getLastName(),
            $contact->getEmail(),
            $date->format('d/m/Y'),
            ($consultants[0] != null) ? $consultants[0]->getId() : '${NUMINTERVENANT}',
            ($consultants[0] != null) ? $this->genderBuilder->getStringGender($consultants[0]->getGender()) : '${CIVILITEINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getFirstName() : '${PRENOMINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getLastName() : '${NOMINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getEmail() : '${MAILINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getLine1() . (($consultants[0]->getAddress()->getLine2() != null) ? ' ' . $consultants[0]->getAddress()->getLine2() : '') : '${ADRESSEINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getPostalCode() : '${CPINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getCity() : '${VILLEINTERVENANT}',
            $connectedUser->getLastName(),
            $connectedUser->getFirstName(),
            $this->genderBuilder->getStringGender($connectedUser->getGender()),
            $this->genderBuilder->getStringGender($contact->getGender()) . ' ' . $contact->getLastName() . ' ' . $contact->getFirstName(),
            $consultantsIdentity,
            $this->genderBuilder->getStringGender($connectedUser->getGender()) . ' ' . $connectedUser->getLastName() . ' ' . $connectedUser->getFirstName(),
            ($study->getArchivedDate() != null) ? $study->getArchivedDate()->format('d/m/Y') : '${DATEFIN}',
            ($president != null) ? $president->getLastName() : '${NOMPRESIDENT}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) : '${CIVPRESIDENT}',
            ($president != null) ? $president->getFirstName() : '${PRENOMPRESIDENT}',
            ($tresorier != null) ? $tresorier->getLastName() : '${NOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) : '${CIVTRESORIER}',
            ($tresorier != null) ? $tresorier->getFirstName() : '${PRENOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) . ' ' . $tresorier->getLastName() . ' ' . $tresorier->getFirstName() : '${IDENTITETRESORIER}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) . ' ' . $president->getLastName() . ' ' . $president->getFirstName() : '${IDENTITEPRESIDENT}',
        );
    }
}