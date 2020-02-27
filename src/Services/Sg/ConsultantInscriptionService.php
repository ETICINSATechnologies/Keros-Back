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
use Keros\Tools\Mail\MailFactory;
use Keros\Tools\Validator;
use Keros\Tools\Helpers\FileHelper;
use Keros\Tools\FileValidator;
use Keros\Tools\Helpers\ConsultantInscriptionFileHelper;
use Keros\Tools\Helpers\ConsultantFileHelper;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;

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

    /** @var MailFactory */
    private $mailFactory;

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
        $this->mailFactory = $container->get(MailFactory::class);
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
        $outYear = Validator::requiredInt($fields["outYear"]);
        $nationalityId = Validator::requiredId($fields["nationalityId"]);
        $nationality = $this->countryService->getOne($nationalityId);
        $address = $this->addressService->create($fields["address"]);
        $socialSecurityNumber = Validator::requiredString($fields["socialSecurityNumber"]);
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $isApprentice = Validator::requiredBool($fields['isApprentice']);
        $createdDate = new \DateTime();
        $documentIdentity = Validator::requiredString($fields['documentIdentity'] ?? null);
        $documentScolaryCertificate = Validator::requiredString($fields['documentScolaryCertificate'] ?? null);
        $documentRIB = Validator::requiredString($fields['documentRIB'] ?? null);
        $documentVitaleCard = Validator::requiredString($fields['documentVitaleCard'] ?? null);
        $documentResidencePermit = Validator::optionalString($fields['documentResidencePermit'] ?? null);
        $documentCVEC = Validator::requiredString($fields['documentCVEC'] ?? null);

        $consultantInscription = new ConsultantInscription($firstName, $lastName, $gender, $birthday, $department, $email, $phoneNumber, $outYear, $nationality, $address, $socialSecurityNumber, $droitImage, $isApprentice, $createdDate, $documentIdentity, $documentScolaryCertificate, $documentRIB, $documentVitaleCard, $documentResidencePermit, $documentCVEC);

        $this->consultantInscriptionDataService->persist($consultantInscription);

        $this->mailFactory->sendMailCreateConsultantInscriptionFromTemplate($consultantInscription);

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

        $consultantInscriptionFiles = ConsultantInscriptionFileHelper::getConsultantInscriptionFiles();

        foreach ($consultantInscriptionFiles as $consultantInscriptionFile) {
            $getFunction = $consultantInscriptionFile['get'];
            $filename = $consultantInscription->$getFunction();
            if ($filename) $this->directoryManager->deleteFile($this->kerosConfig[$consultantInscriptionFile['directory_key']] . $filename);
        }

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
        $phoneNumber = Validator::optionalPhone(isset($fields["phoneNumber"]) ? $fields["phoneNumber"] : $consultantInscription->getPhoneNumber());
        $outYear = Validator::optionalInt(isset($fields["outYear"]) ? $fields["outYear"] : $consultantInscription->getOutYear());
        $nationalityId = Validator::requiredId($fields["nationalityId"]);
        $nationality = $this->countryService->getOne($nationalityId);
        $address = $this->addressService->create($fields["address"]);
        $socialSecurityNumber = Validator::requiredString($fields["socialSecurityNumber"]);
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $isApprentice = Validator::requiredBool($fields['isApprentice']);

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
        $consultantInscription->setIsApprentice($isApprentice);
        $consultantInscription->setSocialSecurityNumber($socialSecurityNumber);

        $this->consultantInscriptionDataService->persist($consultantInscription);

        return $consultantInscription;
    }

    /**
     * @param int $id
     * @return \Keros\Entities\Core\Consultant
     * @throws KerosException
     */
    public function validateConsultantInscription(int $id)
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $date = new \DateTime();
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        $consultantArray = array(
            "username" => $consultantInscription->getFirstName() . '.' . $consultantInscription->getLastName(),
            "password" => $consultantInscription->getFirstName() . '.' . $consultantInscription->getBirthday()->format('d/m/Y'),
            "firstName" => $consultantInscription->getFirstName(),
            "lastName" => $consultantInscription->getLastName(),
            "email" => $consultantInscription->getEmail(),
            "nationalityId" => $consultantInscription->getNationality()->getId(),
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
            "socialSecurityNumber" => $consultantInscription->getSocialSecurityNumber(),
            "droitImage" => $consultantInscription->isDroitImage(),
            "isApprentice" => $consultantInscription->getIsApprentice(),
            "createdDate" => new \DateTime(),
            'isGraduate' => false,
        );

        //copy and add files to consultant
        $consultantInscriptionFiles = ConsultantInscriptionFileHelper::getConsultantInscriptionFiles();
        $consultantFiles = ConsultantFileHelper::getConsultantFiles();

        foreach ($consultantInscriptionFiles as $consultantInscriptionFile) {
            $getFunction = $consultantInscriptionFile['get'];
            $filename = $consultantInscription->$getFunction();
            if ($filename) {
                $filepath = $this->kerosConfig[$consultantInscriptionFile['directory_key']] . $filename;
                $consultantInscriptionFile = $consultantFiles[$consultantInscriptionFile['name']] ?? null;
                if ($consultantInscriptionFile && file_exists($filepath)) {
                    $newDirectory = $this->kerosConfig[$consultantInscriptionFile['directory_key']];
                    $newFilename = FileHelper::safeCopyFileToDirectory($filepath, $newDirectory);
                    $consultantArray[$consultantInscriptionFile['name']] = $newFilename;
                }
            }
        }

        if ($consultantInscription->getOutYear()) {
            $schoolYear = 5 - ($consultantInscription->getOutYear() - $year);
            if ($month > 8 && $month <= 12) //between September and December
                $schoolYear += 1;
            $consultantArray["schoolYear"] = $schoolYear;
        }

        $consultant = $this->consultantService->create($consultantArray);
        $this->delete($consultantInscription->getId());

        $this->mailFactory->sendMailConsultantValidationFromTemplate($consultant, $consultantArray["password"]);

        return $consultant;
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getDocument(int $id, string $document_name): String
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $consultantInscriptionFile = ConsultantInscriptionFileHelper::getConsultantInscriptionFiles()[$document_name];
        $getFunction = $consultantInscriptionFile['get'];
        $filename = FileHelper::verifyFilename($consultantInscription->$getFunction(), $consultantInscriptionFile['name']);
        $filepath = FileHelper::verifyFilepath($this->kerosConfig[$consultantInscriptionFile['directory_key']] . $filename, $consultantInscriptionFile['name']);
        return $filepath;
    }

    /**
     * @param int $id
     * @param string $document_name
     * @param string $file
     * @return ConsultantInscription
     * @throws KerosException
     */
    public function createDocument(int $id, string $document_name, string $file): ConsultantInscription
    {
        $id = Validator::requiredId($id);
        $consultantInscription = $this->getOne($id);
        $consultantInscriptionFile = ConsultantInscriptionFileHelper::getConsultantInscriptionFiles()[$document_name];
        $getFunction = $consultantInscriptionFile['get'];
        $oldFilename = $consultantInscription->$getFunction();
        $validator = $consultantInscriptionFile['string_validator'];
        $filename = Validator::$validator($file);
        $setFunction = $consultantInscriptionFile['set'];
        $consultantInscription->$setFunction($filename);
        $this->consultantInscriptionDataService->persist($consultantInscription);
        if ($oldFilename) $this->directoryManager->deleteFile($this->kerosConfig[$consultantInscriptionFile['directory_key']] . $oldFilename);
        return $consultantInscription;
    }

    /**
     * @param int $id
     * @param string $document_name
     * @param string $file
     * @return array
     * @throws KerosException
     */
    public function getFileDetailsFromUploadedFiles(array $uploadedFiles, array $consultantInscriptionFile): ?array
    {
        $documenArray = null;
        //get validator function name
        $validatorFunction = $consultantInscriptionFile['validator'];
        //get file
        if (array_key_exists($consultantInscriptionFile['name'], $uploadedFiles)) {
            $document = FileValidator::$validatorFunction($uploadedFiles[$consultantInscriptionFile['name']]);
            if ($document) {
                //get filename
                $documentFilename = $document ? $this->directoryManager->uniqueFilenameOnly($document->getClientFileName(), false, $this->kerosConfig[$consultantInscriptionFile['directory_key']]) : null;
                //get filepath
                $documentFilepath = $document ? $this->kerosConfig[$consultantInscriptionFile['directory_key']] . $documentFilename : null;
                //make directory and store filepath
                $this->directoryManager->mkdir($this->kerosConfig[$consultantInscriptionFile['directory_key']]);
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
