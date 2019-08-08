<?php


namespace Keros\Services\Sg;

use Keros\DataServices\Sg\ConsultantInscriptionDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\CountryService;
use Keros\Services\Core\DepartmentService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\ConsultantService;
use Keros\Tools\Validator;
use Keros\Tools\FileValidator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use DateTime;

class   ConsultantInscriptionService
{
    /**
     * @var AddressService
     */
    private $addressService;

    /**
     * @var GenderService
     */
    private $genderService;

    /**
     * @var ConsultantService
     */
    private $consultantService;

    /**
     * @var ConsultantInscriptionDataService
     */
    private $consultantInscriptionDataService;

    /**
     * @var CountryService
     */
    private $countryService;

    /**
     * @var DepartmentService
     */
    private $departmentService;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->addressService = $container->get(AddressService::class);
        $this->genderService = $container->get(GenderService::class);
        $this->countryService = $container->get(CountryService::class);
        $this->departmentService = $container->get(DepartmentService::class);
        $this->consultantService = $container->get(ConsultantService::class);
        $this->consultantInscriptionDataService = $container->get(ConsultantInscriptionDataService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    /**
     * @param array $fields
     * @return ConsultantInscription
     * @throws KerosException
     */
    public function create(array $fields): ConsultantInscription
    {
        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $genderId = Validator::requiredId($fields['genderId']);
        $gender = $this->genderService->getOne($genderId);
        $birthday = Validator::requiredDate($fields['birthday']);
        $departmentId = Validator::requiredId($fields["departmentId"]);
        $department = $this->departmentService->getOne($departmentId);
        $email = Validator::requiredEmail($fields["email"]);
        $phoneNumber = Validator::optionalPhone(isset($fields["phoneNumber"]) ? $fields["phoneNumber"] : null);
        $outYear = Validator::optionalInt(isset($fields["outYear"]) ? $fields["outYear"] : null);
        $nationalityId = Validator::requiredId($fields["nationalityId"]);
        $nationality = $this->countryService->getOne($nationalityId);
        $address = $this->addressService->create($fields["address"]);
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $documentIdentity = Validator::requiredFile($fields['documentIdentity'],'documentIdentity');
        $documentScolaryCertificate = Validator::requiredFile($fields['documentScolaryCertificate'],'documentScolaryCertificate');
        $documentRIB = Validator::requiredFile($fields['documentRIB'],'documentRIB');
        $documentVitaleCard = Validator::requiredFile($fields['documentVitaleCard'],'documentVitaleCard');
        $documentResidencePermit = Validator::optionalFile($fields['documentResidencePermit']) ? $fields['documentResidencePermit'] : null;
        
        $consultantInscription = new ConsultantInscription($firstName, $lastName, $gender, $birthday, $department, $email, $phoneNumber, $outYear, $nationality, $address, $droitImage, $documentIdentity, $documentScolaryCertificate, $documentRIB, $documentVitaleCard, $documentResidencePermit);

        $this->consultantInscriptionDataService->persist($consultantInscription);

        return $consultantInscription;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);

        $documentIdentityFilenameOld = $consultantInscription->getDocumentIdentity();
        $documentScolaryCertificateFilenameOld = $consultantInscription->getDocumentScolaryCertificate();
        $documentRIBFilenameOld = $consultantInscription->getDocumentRIB();
        $documentVitaleCardFilenameOld = $consultantInscription->getDocumentVitaleCard();
        $documentResidencePermitFilenameOld = $consultantInscription->getDocumentResidencePermit();

        if ($documentIdentityFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentIdentityFilenameOld);
        if ($documentScolaryCertificateFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentScolaryCertificateFilenameOld);
        if ($documentRIBFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentRIBFilenameOld);
        if ($documentVitaleCardFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentVitaleCardFilenameOld);
        if ($documentResidencePermitFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentResidencePermitFilenameOld);

        $this->consultantInscriptionDataService->delete($consultantInscription);
    }

    /**
     * @param int $id
     * @return ConsultantInscription
     * @throws KerosException
     */
    public function getOne(int $id): ConsultantInscription
    {
        $id = Validator::requiredId($id);

        $consultantInscription = $this->consultantInscriptionDataService->getOne($id);
        if (!$consultantInscription) {
            throw new KerosException("The consultantInscription could not be found", 404);
        }
        return $consultantInscription;
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->consultantInscriptionDataService->getAll();
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->consultantInscriptionDataService->getPage($requestParameters);
    }

    /**
     * @param RequestParameters $requestParameters
     * @return int
     */
    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->consultantInscriptionDataService->getCount($requestParameters);
    }

    /**
     * @param int $id
     * @param array|null $fields
     * @return ConsultantInscription
     * @throws KerosException
     */
    public function update(int $id, ?array $fields): ConsultantInscription
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);

        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $genderId = Validator::requiredId($fields['genderId']);
        $gender = $this->genderService->getOne($genderId);
        $birthday = Validator::requiredDate($fields['birthday']);
        $departmentId = Validator::requiredId($fields["departmentId"]);
        $department = $this->departmentService->getOne($departmentId);
        $email = Validator::requiredEmail($fields["email"]);
        $phoneNumber = Validator::optionalPhone(isset($fields["phoneNumber"]) ? $fields["phoneNumber"] : null);
        $outYear = Validator::optionalInt(isset($fields["outYear"]) ? $fields["outYear"] : null);
        $nationalityId = Validator::requiredId($fields["nationalityId"]);
        $nationality = $this->countryService->getOne($nationalityId);
        $address = $this->addressService->create($fields["address"]);
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $documentIdentity = Validator::requiredFile($fields['documentIdentity'],'documentIdentity');
        $documentScolaryCertificate = Validator::requiredFile($fields['documentScolaryCertificate'],'documentScolaryCertificate');
        $documentRIB = Validator::requiredFile($fields['documentRIB'],'documentRIB');
        $documentVitaleCard = Validator::requiredFile($fields['documentVitaleCard'],'documentVitaleCard');
        $documentResidencePermit = Validator::optionalFile($fields['documentResidencePermit']) ? $fields['documentResidencePermit'] : null;

        $documentIdentityFilenameOld = $consultantInscription->getDocumentIdentity();
        $documentScolaryCertificateFilenameOld = $consultantInscription->getDocumentScolaryCertificate();
        $documentRIBFilenameOld = $consultantInscription->getDocumentRIB();
        $documentVitaleCardFilenameOld = $consultantInscription->getDocumentVitaleCard();
        $documentResidencePermitFilenameOld = $consultantInscription->getDocumentResidencePermit();

        $consultantInscription->setFirstName($firstName);
        $consultantInscription->setLastName($lastName);
        $consultantInscription->setGender($gender);
        $consultantInscription->setBirthday($birthday);
        $consultantInscription->setDepartment($department);
        $consultantInscription->setEmail($email);
        $consultantInscription->setPhoneNumber($phoneNumber);
        $consultantInscription->setOutYear($outYear);
        $consultantInscription->setNationality($nationality);
        $this->addressService->update($consultantInscription->getAddress()->getId(), $fields["address"]);
        $consultantInscription->setDroitImage($droitImage);
        $consultantInscription->setDocumentIdentity($documentIdentity);
        $consultantInscription->setDocumentScolaryCertificate($documentScolaryCertificate);
        $consultantInscription->setDocumentRIB($documentRIB);
        $consultantInscription->setDocumentVitaleCard($documentVitaleCard);
        $consultantInscription->setDocumentResidencePermit($documentResidencePermit);
        
        $this->consultantInscriptionDataService->persist($consultantInscription);

        if ($documentIdentityFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentIdentityFilenameOld);
        if ($documentScolaryCertificateFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentScolaryCertificateFilenameOld);
        if ($documentRIBFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentRIBFilenameOld);
        if ($documentVitaleCardFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentVitaleCardFilenameOld);
        if ($documentResidencePermitFilenameOld) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $documentResidencePermitFilenameOld);

        return $consultantInscription;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function validateConsultantInscription(int $id)
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $date = new DateTime();
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        $consultantArray = array(
            "username" => $consultantInscription->getFirstName() . '.' . $consultantInscription->getLastName(),
            "password" => $consultantInscription->getFirstName() . '.' . $consultantInscription->getBirthday()->format('d/m/Y'),
            "firstName" => $consultantInscription->getFirstName(),
            "lastName" => $consultantInscription->getLastName(),
            "email" => $consultantInscription->getEmail(),
            "telephone" => $consultantInscription->getPhoneNumber(),
            "birthday" => $consultantInscription->getBirthday()->format('Y-m-d'),
            "genderId" => $consultantInscription->getGender()->getId(),
            "departmentId" => $consultantInscription->getDepartment()->getId(),
            "company" => null,
            "profilePicture" => null,
            "disabled" => false,
            "address" => array(
                "line1" => $consultantInscription->getAddress()->getLine1(),
                "line2" => $consultantInscription->getAddress()->getLine2(),
                "postalCode" => $consultantInscription->getAddress()->getPostalCode(),
                "city" => $consultantInscription->getAddress()->getCity(),
                "countryId" => $consultantInscription->getAddress()->getCountry()->getId()
            ),
            "positions" => array(),
            "droitImage" => $consultantInscription->isDroitImage()
        );

        if ($consultantInscription->getOutYear()) {
            $schoolYear = 5 - ($consultantInscription->getOutYear() - $year);
            if($month > 8 && $month <= 12) //between September and December
                $schoolYear += 1;
            $consultantArray["schoolYear"] = $schoolYear;
        }

        $this->consultantService->create($consultantArray);
        $this->delete($consultantInscription->getId());
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocumentIdentity(int $id): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $filename = FileValidator::verifyFilename($consultantInscription->getDocumentIdentity(),'documentIdentity');
        $filepath =  FileValidator::verifyFilepath($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $filename,'documentIdentity');
        return $filepath;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocumentScolaryCertificate(int $id): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $filename = FileValidator::verifyFilename($consultantInscription->getDocumentScolaryCertificate(),'documentScolaryCertificate');
        $filepath =  FileValidator::verifyFilepath($this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . $filename,'documentScolaryCertificate');
        return $filepath;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocumentRIB(int $id): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $filename = FileValidator::verifyFilename($consultantInscription->getDocumentRIB(),'documentRIB');
        $filepath =  FileValidator::verifyFilepath($this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . $filename,'documentRIB');
        return $filepath;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocumentVitaleCard(int $id): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $filename = FileValidator::verifyFilename($consultantInscription->getDocumentVitaleCard(),'documentVitaleCard');
        $filepath =  FileValidator::verifyFilepath($this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . $filename,'documentVitaleCard');
        return $filepath;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocumentResidencePermit(int $id): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $filename = FileValidator::verifyFilename($consultantInscription->getDocumentResidencePermit(),'documentResidencePermit');
        $filepath =  FileValidator::verifyFilepath($this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . $filename,'documentResidencePermit');
        return $filepath;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function createDocumentIdentity(int $id, array $fields): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $oldFilename = $consultantInscription->getDocumentIdentity();
        $filename = Validator::requiredFile($fields['documentIdentity'],'documentIdentity');
        $consultantInscription->setDocumentIdentity($filename);
        $this->consultantInscriptionDataService->persist($consultantInscription);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . $oldFilename);
        return $filename;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function createDocumentScolaryCertificate(int $id, array $fields): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $oldFilename = $consultantInscription->getDocumentScolaryCertificate();
        $filename = Validator::requiredFile($fields['documentScolaryCertificate'],'documentScolaryCertificate');
        $consultantInscription->setDocumentScolaryCertificate($filename);
        $this->consultantInscriptionDataService->persist($consultantInscription);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . $oldFilename);
        return $filename;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function createDocumentRIB(int $id, array $fields): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $oldFilename = $consultantInscription->getDocumentRIB();
        $filename = Validator::requiredFile($fields['documentRIB'],'documentRIB');
        $consultantInscription->setDocumentRIB($filename);
        $this->consultantInscriptionDataService->persist($consultantInscription);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . $oldFilename);
        return $filename;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function createDocumentVitaleCard(int $id, array $fields): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $oldFilename = $consultantInscription->getDocumentVitaleCard();
        $filename = Validator::requiredFile($fields['documentVitaleCard'],'documentVitaleCard');
        $consultantInscription->setDocumentVitaleCard($filename);
        $this->consultantInscriptionDataService->persist($consultantInscription);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . $oldFilename);
        return $filename;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function createDocumentResidencePermit(int $id, array $fields): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $oldFilename = $consultantInscription->getDocumentResidencePermit();
        $filename = Validator::optionalFile($fields['documentResidencePermit']) ? $fields['documentResidencePermit'] : null;
        $consultantInscription->setDocumentResidencePermit($filename);
        $this->consultantInscriptionDataService->persist($consultantInscription);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . $oldFilename);
        return $filename;
    }

}