<?php


namespace Keros\Services\Treso;

use DateTime;
use Keros\DataServices\Treso\PaymentSlipDataService;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\PaymentSlip;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\PositionService;
use Keros\Services\Core\UserService;
use Keros\Services\Ua\ContactService;
use Keros\Services\Ua\FieldService;
use Keros\Services\Ua\FirmService;
use Keros\Services\Ua\ProvenanceService;
use Keros\Services\Ua\StatusService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class PaymentSlipService
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
     * @var FirmService
     */
    private $firmService;
    /**
     * @var PositionService
     */
    private $positionService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var MemberService
     */
    private $memberService;
    /**
     * @var FieldService
     */
    private $fieldService;
    /**
     * @var StatusService
     */
    private $statusService;
    /**
     * @var ProvenanceService
     */
    private $provenanceService;


    /**
     * @var PaymentSlipDataService
     */
    private $paymentSlipDataService;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->addressService = $container->get(AddressService::class);
        $this->genderService = $container->get(GenderService::class);
        $this->firmService = $container->get(FirmService::class);
        $this->positionService = $container->get(PositionService::class);
        $this->userService = $container->get(UserService::class);
        $this->contactService = $container->get(ContactService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->fieldService = $container->get(FieldService::class);
        $this->statusService = $container->get(StatusService::class);
        $this->provenanceService = $container->get(ProvenanceService::class);
        $this->paymentSlipDataService = $container->get(PaymentSlipDataService::class);
    }

    /**
     * @param array $fields
     * @return PaymentSlip
     * @throws \Exception
     */
    public function create(array $fields): PaymentSlip
    {
        $missionRecapNumber = Validator::optionalString($fields["missionRecapNumber"]);
        $consultantName = Validator::optionalString($fields["consultantName"]);
        $consultantSocialSecurityNumber = Validator::optionalString($fields["consultantSocialSecurityNumber"]);
        $line1 = Validator::requiredString($fields["line1"]);
        $line2 = Validator::optionalString($fields["line2"]);
        $city = Validator::requiredString($fields["city"]);
        $postalCode = Validator::requiredString($fields["postalCode"]);
        $countryId = Validator::requiredId($fields["countryId"]);
        $address = new Address($line1, $line2, $postalCode, $city, $countryId);
        $email = Validator::optionalString($fields["email"]);
        $studyId = Validator::requiredId($fields["studyId"]);
        $consultantId = Validator::requiredId($fields["consultantId"]);
        $consultant = $this->memberService->getOne($consultantId);
        $clientName = Validator::optionalString($fields["clientName"]);
        $projectLead = Validator::optionalString($fields["projectLead"]);
        $isTotalJeh = Validator::optionalBool($fields["isTotalJeh"]);
        $isStudyPaid = Validator::optionalBool($fields["isStudyPaid"]);
        $amountDescription = Validator::optionalString($fields["amountDescription"]);
        $createdBy = Validator::requiredId($fields["createdBy"]);
        $creator = $this->memberService->getOne($createdBy);
        $date = new \DateTime();

        $paymentSlip = new PaymentSlip($missionRecapNumber, $consultantName, $consultantSocialSecurityNumber, $address, $email, $studyId, $clientName, $projectLead, $consultant, $isTotalJeh, $isStudyPaid, $amountDescription, $date, $creator, false, null, null, false, null, null);

        $this->paymentSlipDataService->persist($paymentSlip);

        return $paymentSlip;
    }

    public function getOne(int $id): PaymentSlip
    {
        $id = Validator::requiredId($id);

        $paymentSlip = $this->paymentSlipDataService->getOne($id);
        if (!$paymentSlip) {
            throw new KerosException("The paymentSlip could not be found", 404);
        }
        return $paymentSlip;
    }

    public function validateUA(int $id, int $idUA) : PaymentSlip
    {
        $id = Validator::requiredId($id);
        $idUA = Validator::requiredId($idUA);

        $paymentSlip = $this->paymentSlipDataService->getOne($id);
        $UAMember = $this->memberService->getOne($idUA);
        $dateString = date("d/m/Y");
        $date = DateTime::createFromFormat('d/m/Y', $dateString);
        $paymentSlip->setValidatedByUa(true);
        $paymentSlip->setValidatedByUaDate($date);
        $paymentSlip->setValidatedByUaMember($UAMember);

        $this->paymentSlipDataService->persist($paymentSlip);

        return $paymentSlip;
    }

    public function validatePerf(int $id, int $idPerf): PaymentSlip
    {
        $id = Validator::requiredId($id);
        $idPerf = Validator::requiredId($idPerf);

        $paymentSlip = $this->paymentSlipDataService->getOne($id);
        $PerfMember = $this->memberService->getOne($idPerf);
        $dateString = date("d/m/Y");
        $date = DateTime::createFromFormat('d/m/Y', $dateString);

        $paymentSlip->setvalidatedByPerf(true);
        $paymentSlip->setValidatedByPerfDate($date);
        $paymentSlip->setValidatedByPerfMember($PerfMember);

        $this->paymentSlipDataService->persist($paymentSlip);

        return $paymentSlip;
    }

    public function getAll(): array
    {
        return $this->paymentSlipDataService->getAll();
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->paymentSlipDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->paymentSlipDataService->getCount($requestParameters);
    }

}