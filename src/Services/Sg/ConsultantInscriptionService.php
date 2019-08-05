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
     * @var MemberService
     */
    private $memberService;

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

        $documentIdentityFilename = $this->directoryManager->uniqueFilename($documentIdentity, false, $this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY']);
        $documentScolaryCertificateFilename = $this->directoryManager->uniqueFilename($documentScolaryCertificate, false, $this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY']);
        $documentRIBFilename = $this->directoryManager->uniqueFilename($documentRIB, false, $this->kerosConfig['INSCRIPTION_RIB_DIRECTORY']);
        $documentVitaleCardFilename = $this->directoryManager->uniqueFilename($documentVitaleCard, false, $this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY']);
        $documentResidencePermitFilename = $documentResidencePermit ? $this->directoryManager->uniqueFilename($documentResidencePermit, false, $this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY']) : null;

        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'] . pathinfo($documentIdentityFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'] . pathinfo($documentScolaryCertificateFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_RIB_DIRECTORY'] . pathinfo($documentRIBFilename, PATHINFO_DIRNAME));
        $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_VITALE_CARD_DIRECTORY'] . pathinfo($documentVitaleCardFilename, PATHINFO_DIRNAME));
        if ($documentResidencePermit) $this->directoryManager->mkdir($this->kerosConfig['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'] . pathinfo($documentResidencePermitFilename, PATHINFO_DIRNAME));
        
        $consultantInscription = new ConsultantInscription($firstName, $lastName, $gender, $birthday, $department, $email, $phoneNumber, $outYear, $nationality, $address, $droitImage, $documentIdentityFilename, $documentScolaryCertificateFilename, $documentRIBFilename, $documentVitaleCardFilename, $documentResidencePermitFilename);

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

}