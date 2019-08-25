<?php

namespace Keros\Services\Treso;

use Keros\DataServices\Treso\FactureDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\Facture;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\MemberService;
use Keros\Services\Ua\ContactService;
use Keros\Services\Ua\StudyService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class FactureService
{
    /**
     * @var AddressService
     */
    private $addressService;

    /**
     * @var ContactService
     */
    private $contactService;

    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var StudyService
     */
    private $studyService;

    /**
     * @var FactureTypeService
     */
    private $factureTypeService;

    /**
     * @var FactureDataService
     */
    private $factureDataService;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->addressService = $container->get(AddressService::class);
        $this->contactService = $container->get(ContactService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->factureDataService = $container->get(FactureDataService::class);
        $this->studyService = $container->get(StudyService::class);
        $this->factureTypeService = $container->get(FactureTypeService::class);
    }

    /**
     * @param array $fields
     * @return Facture
     * @throws \Exception
     */
    public function create(array $fields): Facture
    {
        $numero = Validator::optionalString(isset($fields["numero"]) ? $fields["numero"] : null);
        if (isset($fields["fullAddress"]))
            $address = $this->addressService->create($fields["fullAddress"]);
        else
            $address = null;
        $clientName = Validator::optionalString(isset($fields["clientName"]) ? $fields["clientName"] : null);
        $contactName = Validator::optionalString(isset($fields["contactName"]) ? $fields["contactName"] : null);
        $contactEmail = Validator::optionalString(isset($fields["contactEmail"]) ? $fields["contactEmail"] : null);
        $studyId = Validator::optionalId(isset($fields["studyId"]) ? $fields["studyId"] : null);
        if($studyId != null)
            $study = $this->studyService->getOne($studyId);
        else
            $study = null;

        $typeId = Validator::requiredString($fields["type"]);
        $type = $this->factureTypeService->getFromLabel($typeId);
        $amountDescription = Validator::optionalString(isset($fields["amountDescription"]) ? $fields["amountDescription"] : null);
        $subject = Validator::optionalString(isset($fields["subject"]) ? $fields["subject"] : null);
        $agreementSignDate = Validator::optionalDate(isset($fields["agreementSignDate"]) ? $fields["agreementSignDate"] : null);
        $amountHT = Validator::optionalFloat(isset($fields["amountHT"]) ? $fields["amountHT"] : null);
        $taxPercentage = Validator::optionalFloat(isset($fields["taxPercentage"]) ? $fields["taxPercentage"] : null);
        $dueDate = Validator::optionalDate(isset($fields["dueDate"]) ? $fields["dueDate"] : null);
        $additionalInformation = Validator::optionalString(isset($fields["additionalInformation"]) ? $fields["additionalInformation"] : null);
        $createdById = Validator::requiredId($fields["createdBy"]);
        $createdBy = $this->memberService->getOne($createdById);

        $facture = new Facture($numero, $address, $clientName, $contactName, $contactEmail, $study, $type, $amountDescription, $subject, $agreementSignDate, $amountHT, $taxPercentage, $dueDate, $additionalInformation, new \DateTime(), $createdBy, false, null, null, false, null, null);
        $this->factureDataService->persist($facture);

        return $facture;
    }

    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $facture = $this->getOne($id);
        $this->factureDataService->delete($facture);
    }

    public function getOne(int $id): Facture
    {
        $id = Validator::requiredId($id);
        $facture = $this->factureDataService->getOne($id);
        if (!$facture) {
            throw new KerosException("The facture could not be found", 404);
        }
        return $facture;
    }

    /**
     * @return Facture[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->factureDataService->getAll();
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->factureDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->factureDataService->getCount($requestParameters);
    }

    /**
     * @param int $id
     * @param array|null $fields
     * @return Facture
     * @throws KerosException
     */
    public function update(int $id, ?array $fields): Facture
    {
        $facture = $this->getOne($id);
        $numero = Validator::optionalString(isset($fields["numero"]) ? $fields["numero"] : null);
        if (isset($fields["fullAddress"]))
            $address = $this->addressService->create($fields["fullAddress"]);
        else
            $address = null;
        $clientName = Validator::optionalString(isset($fields["clientName"]) ? $fields["clientName"] : null);
        $contactName = Validator::optionalString(isset($fields["contactName"]) ? $fields["contactName"] : null);
        $contactEmail = Validator::optionalString(isset($fields["contactEmail"]) ? $fields["contactEmail"] : null);
        $studyId = Validator::optionalId(isset($fields["studyId"]) ? $fields["studyId"] : null);
        if($studyId != null)
            $study = $this->studyService->getOne($studyId);
        else
            $study = null;

        $typeId = Validator::requiredString($fields["type"]);
        $type = $this->factureTypeService->getFromLabel($typeId);
        $amountDescription = Validator::optionalString(isset($fields["amountDescription"]) ? $fields["amountDescription"] : null);
        $subject = Validator::optionalString(isset($fields["subject"]) ? $fields["subject"] : null);
        $agreementSignDate = Validator::optionalDate(isset($fields["agreementSignDate"]) ? $fields["agreementSignDate"] : null);
        $amountHT = Validator::optionalFloat(isset($fields["amountHT"]) ? $fields["amountHT"] : null);
        $taxPercentage = Validator::optionalFloat(isset($fields["taxPercentage"]) ? $fields["taxPercentage"] : null);
        $dueDate = Validator::optionalDate(isset($fields["dueDate"]) ? $fields["dueDate"] : null);
        $additionalInformation = Validator::optionalString(isset($fields["additionalInformation"]) ? $fields["additionalInformation"] : null);

        $facture->setNumero($numero);
        $facture->setFullAddress($address);
        $facture->setClientName($clientName);
        $facture->setContactName($contactName);
        $facture->setContactEmail($contactEmail);
        $facture->setStudy($study);
        $facture->setType($type);
        $facture->setAmountDescription($amountDescription);
        $facture->setSubject($subject);
        $facture->setAgreementSignDate($agreementSignDate);
        $facture->setAmountHT($amountHT);
        $facture->setTaxPercentage($taxPercentage);
        $facture->setDueDate($dueDate);
        $facture->setAdditionalInformation($additionalInformation);

        $this->factureDataService->persist($facture);

        return $facture;
    }

    /**
     * @param int $factureId
     * @param int $validateUaMemberId
     * @return Facture
     * @throws \Exception
     */
    public function validateByUa(int $factureId, int $validateUaMemberId): Facture
    {
        $facture = $this->getOne($factureId);
        $member = $this->memberService->getOne($validateUaMemberId);

        $facture->setValidatedByUa(true);
        $facture->setValidatedByUaMember($member);
        $facture->setValidatedByUaDate(new \DateTime());

        $this->factureDataService->persist($facture);

        return $facture;
    }

    /**
     * @param int $factureId
     * @param int $validateUaMemberId
     * @return Facture
     * @throws \Exception
     */
    public function validateByPerf(int $factureId, int $validateUaMemberId): Facture
    {
        $facture = $this->getOne($factureId);
        $member = $this->memberService->getOne($validateUaMemberId);

        $facture->setValidatedByPerf(true);
        $facture->setValidatedByPerfMember($member);
        $facture->setValidatedByPerfDate(new \DateTime());

        $this->factureDataService->persist($facture);

        return $facture;
    }

}