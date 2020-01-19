<?php

namespace Keros\Services\Core;

use Keros\DataServices\Core\ConsultantDataService;
use Keros\DataServices\Core\TicketDataService;
use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Tools\Helpers\FileHelper;
use Keros\Tools\FileValidator;
use Keros\Tools\Helpers\ConsultantFileHelper;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;

class ConsultantService
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
     * @var UserService
     */
    private $userService;
    /**
     * @var DepartmentService
     */
    private $departmentService;
    /**
     * @var TicketDataService
     */
    private $ticketDataService;
    /**
     * @var ConsultantDataService
     */
    private $consultantDataService;
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
        $this->departmentService = $container->get(DepartmentService::class);
        $this->userService = $container->get(UserService::class);
        $this->consultantDataService = $container->get(ConsultantDataService::class);
        $this->ticketDataService = $container->get(TicketDataService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    /**
     * @param array $fields
     * @return Consultant
     * @throws KerosException
     */
    public function create(array $fields): Consultant
    {
        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::optionalPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : null);
        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::requiredId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);
        $department = $this->departmentService->getOne($departmentId);
        $company = Validator::optionalString($fields["company"]);
        $profilePicture = Validator::optionalString($fields["profilePicture"]);
        $socialSecurityNumber = Validator::optionalString($fields["socialSecurityNumber"]);
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $isApprentice = Validator::requiredBool($fields['isApprentice']);
        $createdDate = new \DateTime();
        $documentIdentity = Validator::optionalString($fields['documentIdentity'] ?? null);
        $documentScolaryCertificate = Validator::optionalString($fields['documentScolaryCertificate'] ?? null);
        $documentRIB = Validator::optionalString($fields['documentRIB'] ?? null);
        $documentVitaleCard = Validator::optionalString($fields['documentVitaleCard'] ?? null);
        $documentResidencePermit = Validator::optionalString($fields['documentResidencePermit'] ?? null);
        $documentCVEC = Validator::optionalString($fields['documentCVEC'] ?? null);

        $consultant = new Consultant($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $gender, $department, $company, $profilePicture, $socialSecurityNumber, $droitImage, $isApprentice, $createdDate, $documentIdentity, $documentScolaryCertificate, $documentRIB, $documentVitaleCard, $documentResidencePermit, $documentCVEC);
        $user = $this->userService->create($fields);
        $address = $this->addressService->create($fields["address"]);
        $consultant->setUser($user);
        $consultant->setAddress($address);

        $this->consultantDataService->persist($consultant);

        return $consultant;
    }

    public function getOne(int $id): Consultant
    {
        $id = Validator::requiredId($id);

        $consultant = $this->consultantDataService->getOne($id);
        if (!$consultant) {
            throw new KerosException("The consultant could not be found", 404);
        }
        return $consultant;
    }

    public function getPage(RequestParameters $requestParameters, array $queryParams): Page
    {
        if (isset($queryParams['year']) && $queryParams['year'] == 'latest') {
            $queryParams['year'] = $this->consultantPositionService->getLatestYear();
        }

        return $this->consultantDataService->getPage($requestParameters, $queryParams);
    }

    public function getSome(array $ids): array
    {
        $consultants = [];
        foreach ($ids as $id) {
            $id = Validator::requiredId($id);
            $consultant = $this->consultantDataService->getOne($id);
            if (!$consultant) {
                throw new KerosException("The consultant could not be found", 404);
            }
            $consultants[] = $consultant;
        }

        return $consultants;
    }

    /**
     * @param int $id
     * @return array
     * @throws KerosException
     */
    public function getOneProtectedData(int $id): array
    {
        $id = Validator::requiredId($id);

        $consultant = $this->consultantDataService->getOne($id);
        if (!$consultant) {
            throw new KerosException("The consultant could not be found", 404);
        }
        $consultantProtectedData = $consultant->getProtected();
        return $consultantProtectedData;
    }

    /**
     * @param int $id
     * @param array|null $fields
     * @return Consultant
     * @throws KerosException
     */
    public function update(int $id, ?array $fields): Consultant
    {
        $id = Validator::requiredId($id);
        $consultant = $this->getOne($id);

        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::optionalPhone(isset($fields["telephone"]) ? $fields["telephone"] : $consultant->getTelephone());
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : $consultant->getSchoolYear());
        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::requiredId(isset($fields["departmentId"]) ? $fields["departmentId"] : $consultant->getDepartment()->getId());
        $department = $this->departmentService->getOne($departmentId);
        $company = Validator::optionalString(isset($fields["company"]) ? $fields["company"] : $consultant->getCompany());
        $profilePicture = Validator::optionalString(isset($fields["profilePicture"]) ? $fields["profilePicture"] : $consultant->getProfilePicture());
        $socialSecurityNumber = Validator::optionalString(isset($fields["socialSecurityNumber"]) ? $fields["socialSecurityNumber"] : $consultant->getSocialSecurityNumber());
        $isApprentice = Validator::requiredBool($fields['isApprentice']);

        $consultant->setFirstName($firstName);
        $consultant->setLastName($lastName);
        $consultant->setEmail($email);
        $consultant->setTelephone($telephone);
        $consultant->setBirthday($birthday);
        $consultant->setSchoolYear($schoolYear);
        $consultant->setGender($gender);
        $consultant->setDepartment($department);
        $consultant->setCompany($company);
        $consultant->setProfilePicture($profilePicture);
        $consultant->setIsApprentice($isApprentice);
        $consultant->setSocialSecurityNumber($socialSecurityNumber);

        $this->addressService->update($consultant->getAddress()->getId(), $fields["address"]);
        $this->userService->update($consultant->getId(), $fields);

        $this->consultantDataService->persist($consultant);

        return $consultant;
    }

    public function delete(int $id)
    {
        $id = Validator::requiredId($id);
        $consultant = $this->getOne($id);
        $address = $consultant->getAddress();

        $consultant->setStudiesAsConsultant([]);
        $this->consultantDataService->persist($consultant);

        $consultantFiles = ConsultantFileHelper::getConsultantFiles();
        foreach ($consultantFiles as $consultantFile) {
            $getFunction = $consultantFile['get'];
            $filename = $consultant->$getFunction();
            if ($filename) $this->directoryManager->deleteFile($this->kerosConfig[$consultantFile['directory_key']] . $filename);
        }

        $this->consultantDataService->delete($consultant);
        $this->userService->delete($id);
        $this->addressService->delete($address->getId());
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocument(int $id, string $document_name): String
    {
        $id = Validator::requiredId($id);
        $consultant = $this->getOne($id);
        $consultantFile = ConsultantFileHelper::getConsultantFiles()[$document_name];
        $getFunction = $consultantFile['get'];
        $filename = FileHelper::verifyFilename($consultant->$getFunction(), $consultantFile['name']);
        $filepath =  FileHelper::verifyFilepath($this->kerosConfig[$consultantFile['directory_key']] . $filename, $consultantFile['name']);
        return $filepath;
    }

    /**
     * @param int $id
     * @param string $document_name
     * @param string $file
     * @return Consultant
     * @throws KerosException
     */
    public function createDocument(int $id, string $document_name, string $file): Consultant
    {
        $id = Validator::requiredId($id);
        $consultant = $this->getOne($id);
        $consultantFile = ConsultantFileHelper::getConsultantFiles()[$document_name];
        $getFunction = $consultantFile['get'];
        $oldFilename = $consultant->$getFunction();
        $validator = $consultantFile['string_validator'];
        $filename = Validator::$validator($file);
        $setFunction = $consultantFile['set'];
        $consultant->$setFunction($filename);
        $this->consultantDataService->persist($consultant);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig[$consultantFile['directory_key']] . $oldFilename);
        return $consultant;
    }

    /**
     * @param int $id
     * @param string $document_name
     * @param string $file
     * @return array
     * @throws KerosException
     */
    public function getFileDetailsFromUploadedFiles(array $uploadedFiles, array $consultantFile): ?array
    {
        $documenArray = null;
        //get validator function name
        $validatorFunction = $consultantFile['validator'];
        //get file
        if (array_key_exists($consultantFile['name'], $uploadedFiles)) {
            $document = FileValidator::$validatorFunction($uploadedFiles[$consultantFile['name']]);
            if ($document) {
                //get filename
                $documentFilename = $document ? $this->directoryManager->uniqueFilenameOnly($document->getClientFileName(), false, $this->kerosConfig[$consultantFile['directory_key']]) : null;
                //get filepath
                $documentFilepath = $document ? $this->kerosConfig[$consultantFile['directory_key']] . $documentFilename : null;
                //make directory and store filepath
                $this->directoryManager->mkdir($this->kerosConfig[$consultantFile['directory_key']]);
                $documenArray = array(
                    'file' => $document,
                    'filepath' => $documentFilepath,
                    'filename' => $documentFilename,
                );
            }
        }

        return $documenArray;
    }
}
