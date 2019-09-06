<?php


namespace Keros\Services\Treso;

use DateTime;
use Keros\DataServices\Treso\PaymentSlipDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\PaymentSlip;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\MemberService;
use Keros\Services\Ua\StudyService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DocumentGenerator;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Exception;

class PaymentSlipService
{
    /**
     * @var AddressService
     */
    private $addressService;
    /**
     * @var MemberService
     */
    private $memberService;
    /**
     * @var StudyService
     */
    private $studyService;
    /**
     * @var PaymentSlipDataService
     */
    private $paymentSlipDataService;
    /**
     * @var Logger
     */
    private $logger;

    /** @var array */
    private $kerosConfig;

    /** @var PaymentSlipDocumentTypeService */
    private $paymentSlipDocumentTypeService;

    /** @var DocumentGenerator */
    private $documentGenerator;

    /**
     * PaymentSlipService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->addressService = $container->get(AddressService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->paymentSlipDataService = $container->get(PaymentSlipDataService::class);
        $this->studyService = $container->get(StudyService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->paymentSlipDocumentTypeService = $container->get(PaymentSlipDocumentTypeService::class);
        $this->documentGenerator = $container->get(DocumentGenerator::class);
    }

    /**
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function generateDocument(int $id) : string
    {
        $paymentSlip = $this->getOne($id);
        $templateFile = $this->paymentSlipDocumentTypeService->get();

        return $this->documentGenerator->generateSimpleDocument($templateFile, $this->getReplacementArray($paymentSlip));
    }

    /**
     * @param PaymentSlip $paymentSlip
     * @return array
     */
    private function getReplacementArray(PaymentSlip $paymentSlip) : array
    {
        return array(
            '${NUMEROETUDE}' => $paymentSlip->getStudy()->getId() ?? '${NUMEROETUDE}',
            '${NUMINTER}' => '${NUMINTER}',
            '${CIVILITEINTERVENANT}' => '',
            '${NOMINTERVENANT}' => $paymentSlip->getClientName() ?? '${NOMINTERVENANT}',
            '${PRENOMINTERVENANT}' => '',
            '${SECUINTERVENANT}' => $paymentSlip->getConsultantSocialSecurityNumber() ?? '${SECUINTERVENANT}',
            '${ADRESSEINTERVENANT}' => ($paymentSlip->getAddress() != null) ? $paymentSlip->getAddress()->getLine1() . ($paymentSlip->getAddress()->getLine2() ?? '') : '${ADRESSEINTERVENANT}',
            '${CPINTERVENANT}' => ($paymentSlip->getAddress() != null) ? $paymentSlip->getAddress()->getPostalCode() : '${CPINTERVENANT}',
            '${VILLEINTERVENANT}' => ($paymentSlip->getAddress() != null) ? $paymentSlip->getAddress()->getCity() : '${VILLEINTERVENANT}',
            '${MAILCONSULTANT}' => $paymentSlip->getEmail() ?? '${MAILCONSULTANT}',
            '${NOMENTREPRISE}' => $paymentSlip->getClientName() ?? '${NOMENTREPRISE}',
            '${NOMUSER}' => $paymentSlip->getCreatedBy()->getLastName() ?? '${NOMENTREPRISE}',
            '${PRENOMUSER}' => $paymentSlip->getCreatedBy()->getFirstName() ?? '${PRENOMUSER}',
            '${DEPARTEMENT}' => $paymentSlip->getStudy()->getField()->getLabel() ?? '${DEPARTEMENT}',

        );
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);

        $paymentSlip = $this->getOne($id);

        $this->paymentSlipDataService->delete($paymentSlip);
    }

    /**
     * @param array $fields
     * @return PaymentSlip
     * @throws Exception
     */
    public function create(array $fields): PaymentSlip
    {
        $missionRecapNumber = Validator::optionalString(isset($fields["missionRecapNumber"]) ? $fields["missionRecapNumber"] : null);
        $consultantName = Validator::optionalString(isset($fields["consultantName"]) ? $fields["consultantName"] : null);
        $consultantSocialSecurityNumber = Validator::optionalString(isset($fields["consultantSocialSecurityNumber"]) ? $fields["consultantSocialSecurityNumber"] : null);
        if (isset($fields["address"]))
            $address = $this->addressService->create($fields["address"]);
        else
            $address = null;
        $email = Validator::optionalEmail(isset($fields["email"]) ? $fields["email"] : null);
        $studyId = Validator::requiredId($fields["studyId"]);
        $study = $this->studyService->getOne($studyId);
        $clientName = Validator::optionalString(isset($fields["clientName"]) ? $fields["clientName"] : null);
        $projectLead = Validator::optionalString(isset($fields["projectLead"]) ? $fields["projectLead"] : null);
        $isTotalJeh = Validator::optionalBool(isset($fields["isTotalJeh"]) ? $fields["isTotalJeh"] : null);
        $isStudyPaid = Validator::optionalBool(isset($fields["isStudyPaid"]) ? $fields["isStudyPaid"] : null);
        $amountDescription = Validator::optionalString(isset($fields["amountDescription"]) ? $fields["amountDescription"] : null);
        $createdBy = Validator::requiredId($fields["createdBy"]);
        $creator = $this->memberService->getOne($createdBy);
        $date = new DateTime();

        $paymentSlip = new PaymentSlip($missionRecapNumber, $consultantName, $consultantSocialSecurityNumber, $address, $email, $study, $clientName, $projectLead, $isTotalJeh, $isStudyPaid, $amountDescription, $date, $creator, false, null, null, false, null, null);

        $this->paymentSlipDataService->persist($paymentSlip);

        return $paymentSlip;
    }

    /**
     * @param int $id
     * @return PaymentSlip
     * @throws KerosException
     */
    public function getOne(int $id): PaymentSlip
    {
        $id = Validator::requiredId($id);

        $paymentSlip = $this->paymentSlipDataService->getOne($id);
        if (!$paymentSlip) {
            throw new KerosException("The paymentSlip $id could not be found", 404);
        }
        return $paymentSlip;
    }

    /**
     * @param int $id
     * @param int $idUA
     * @throws KerosException
     */
    public function validateUA(int $id, int $idUA)
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

        return;
    }

    /**
     * @param int $id
     * @param int $idPerf
     * @throws KerosException
     */
    public function validatePerf(int $id, int $idPerf)
    {
        $id = Validator::requiredId($id);
        $idPerf = Validator::requiredId($idPerf);

        $paymentSlip = $this->paymentSlipDataService->getOne($id);
        $perfMember = $this->memberService->getOne($idPerf);
        $dateString = date("d/m/Y");
        $date = DateTime::createFromFormat('d/m/Y', $dateString);

        $paymentSlip->setvalidatedByPerf(true);
        $paymentSlip->setValidatedByPerfDate($date);
        $paymentSlip->setValidatedByPerfMember($perfMember);

        $this->paymentSlipDataService->persist($paymentSlip);

        return;
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->paymentSlipDataService->getAll();
    }

    /**
     * @param RequestParameters $requestParameters
     * @return PaymentSlip[]
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->paymentSlipDataService->getPage($requestParameters);
    }

    /**
     * @param RequestParameters $requestParameters
     * @return int
     */
    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->paymentSlipDataService->getCount($requestParameters);
    }

    /**
     * @param int $id
     * @param array|null $fields
     * @return PaymentSlip
     * @throws KerosException
     */
    public function update(int $id, ?array $fields): PaymentSlip
    {
        $id = Validator::requiredId($id);
        $paymentSlip = $this->getOne($id);

        $missionRecapNumber = Validator::optionalString(isset($fields["missionRecapNumber"]) ? $fields["missionRecapNumber"] : $paymentSlip->getMissionRecapNumber());
        $consultantName = Validator::optionalString(isset($fields["consultantName"]) ? $fields["consultantName"] : $paymentSlip->getConsultantName());
        $consultantSocialSecurityNumber = Validator::optionalString(isset($fields["consultantSocialSecurityNumber"]) ? $fields["consultantSocialSecurityNumber"] : $paymentSlip->getConsultantSocialSecurityNumber());
        $email = Validator::optionalEmail(isset($fields["email"]) ? $fields["email"] : $paymentSlip->getEmail());
        $studyId = Validator::requiredId($fields["studyId"]);
        $study = $this->studyService->getOne($studyId);
        $clientName = Validator::optionalString(isset($fields["clientName"]) ? $fields["clientName"] : $paymentSlip->getClientName());
        $projectLead = Validator::optionalString(isset($fields["projectLead"]) ? $fields["projectLead"] : $paymentSlip->getProjectLead());
        $isTotalJeh = Validator::optionalBool(isset($fields["isTotalJeh"]) ? $fields["isTotalJeh"] : $paymentSlip->getisTotalJeh());
        $isStudyPaid = Validator::optionalBool(isset($fields["isStudyPaid"]) ? $fields["isStudyPaid"] : $paymentSlip->getisStudyPaid());
        $amountDescription = Validator::optionalString(isset($fields["amountDescription"]) ? $fields["amountDescription"] : $paymentSlip->getAmountDescription());

        $paymentSlip->setMissionRecapNumber($missionRecapNumber);
        $paymentSlip->setConsultantName($consultantName);
        $paymentSlip->setConsultantSocialSecurityNumber($consultantSocialSecurityNumber);
        if (isset($fields["address"]))
            $this->addressService->update($paymentSlip->getAddress()->getId(), $fields["address"]);
        $paymentSlip->setEmail($email);
        $paymentSlip->setStudy($study);
        $paymentSlip->setClientName($clientName);
        $paymentSlip->setProjectLead($projectLead);
        $paymentSlip->setIsTotalJeh($isTotalJeh);
        $paymentSlip->setIsStudyPaid($isStudyPaid);
        $paymentSlip->setAmountDescription($amountDescription);

        $this->paymentSlipDataService->persist($paymentSlip);

        return $paymentSlip;
    }
}