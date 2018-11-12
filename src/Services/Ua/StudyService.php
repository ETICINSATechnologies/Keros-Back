<?php


namespace Keros\Services\Ua;

use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
use Keros\DataServices\Ua\StudyDataService;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\PositionService;
use Keros\Services\Core\UserService;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class StudyService
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
     * @var
     */
    private $provenanceService;
    /**
     * @var StudyDataService
     */
    private $studyDataService;

    public function __construct(ContainerInterface $container)
    {
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
        $this->studyDataService = $container->get(StudyDataService::class);
    }

    /**
     * @param array $fields
     * @return Study
     * @throws KerosException
     */
    public function create(array $fields): Study
    {
        $number = Validator::requiredInt($fields["number"]);
        $name = Validator::requiredString($fields["name"]);
        $description = Validator::requiredString($fields["description"]);
        
        $fieldId = Validator::requiredId($fields["fieldId"]);
        $field = $this->fieldService->getOne($fieldId);

        $statusId = Validator::requiredId($fields["statusId"]);
        $status = $this->statusService->getOne($statusId);
        
        $firmId = Validator::requiredId($fields["firmId"]);
        $firm = $this->firmService->getOne($firmId);

        $contactIds = $fields["contactIds"];
        $contacts = $this->contactService->getSome($contactIds);

        $leaderIds = $fields["leaderIds"];
        $leaders = $this->memberService->getSome($leaderIds);

        $consultantIds = $fields["consultantIds"];
        $consultants = $this->memberService->getSome($consultantIds);

        $qualityManagerIds = $fields["qualityManagerIds"];
        $qualityManagers = $this->memberService->getSome($qualityManagerIds);

        $study = new Study(
            $number, $name, $description, $field, $status, $firm, $contacts, $leaders, $consultants, $qualityManagers);

        if (isset($fields["provenanceId"])) {
            $provenanceId = Validator::requiredId($fields["provenanceId"]);
            $provenance = $this->provenanceService->getOne($provenanceId);
            $study->setProvenance($provenance);
        }

        if (isset($fields["signDate"])) {
            $signDate = Validator::requiredDate($fields["signDate"]);
            $study->setSignDate($signDate);
        }

        if (isset($fields["endDate"])) {
            $endDate = Validator::requiredDate($fields["endDate"]);
            $study->setEndDate($endDate);
        }

        if (isset($fields["managementFee"])) {
            $managementFee = Validator::requiredInt($fields["managementFee"]);
            $study->setManagementFee($managementFee);
        }

        if (isset($fields["realizationFee"])) {
            $realizationFee = Validator::requiredInt($fields["realizationFee"]);
            $study->setRealizationFee($realizationFee);
        }

        if (isset($fields["rebilledFee"])) {
            $rebilledFee = Validator::requiredInt($fields["rebilledFee"]);
            $study->setRebilledFee($rebilledFee);
        }

        if (isset($fields["ecoparticipationFee"])) {
            $ecoparticipationFee = Validator::requiredInt($fields["ecoparticipationFee"]);
            $study->setEcoparticipationFee($ecoparticipationFee);
        }

        if (isset($fields["outsourcingFee"])) {
            $outsourcingFee = Validator::requiredInt($fields["outsourcingFee"]);
            $study->setOutsourcingFee($outsourcingFee);
        }

        if (isset($fields["archivedDate"])) {
            $archivedDate = Validator::requiredDate($fields["archivedDate"]);
            $study->setArchivedDate($archivedDate);
        }
        
        $this->studyDataService->persist($study);

        return $study;
    }

    public function getOne(int $id): Study
    {
        $id = Validator::requiredId($id);

        $study = $this->studyDataService->getOne($id);
        if (!$study) {
            throw new KerosException("The study could not be found", 404);
        }
        return $study;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->studyDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->studyDataService->getCount($requestParameters);
    }

    public function update(int $id, ?array $fields): Study
    {
        $id = Validator::requiredId($id);
        $study = $this->getOne($id);

        if (isset($fields["number"])) {
            $number = Validator::requiredInt($fields["number"]);
            $study->setNumber($number);
        }

        if (isset($fields["name"])) {
            $name = Validator::requiredString($fields["name"]);
            $study->setName($name);
        }

        if (isset($fields["description"])) {
            $description = Validator::requiredString($fields["description"]);
            $study->setDescription($description);
        }

        if (isset($fields["fieldId"])) {
            $fieldId = Validator::requiredId($fields["fieldId"]);
            $field = $this->fieldService->getOne($fieldId);
            $study->setField($field);
        }
        
        if (isset($fields["provenanceId"])) {
            $provenanceId = Validator::requiredId($fields["provenanceId"]);
            $provenance = $this->provenanceService->getOne($provenanceId);
            $study->setProvenance($provenance);
        }
        
        if (isset($fields["statusId"])) {
            $statusId = Validator::requiredId($fields["statusId"]);
            $status = $this->statusService->getOne($statusId);
            $study->setStatus($status);
        }

        if (isset($fields["signDate"])) {
            $signDate = Validator::requiredDate($fields["signDate"]);
            $study->setSignDate($signDate);
        }

        if (isset($fields["endDate"])) {
            $endDate = Validator::requiredDate($fields["endDate"]);
            $study->setEndDate($endDate);
        }

        if (isset($fields["managementFee"])) {
            $managementFee = Validator::requiredInt($fields["managementFee"]);
            $study->setManagementFee($managementFee);
        }

        if (isset($fields["realizationFee"])) {
            $realizationFee = Validator::requiredInt($fields["realizationFee"]);
            $study->setRealizationFee($realizationFee);
        }

        if (isset($fields["rebilledFee"])) {
            $rebilledFee = Validator::requiredInt($fields["rebilledFee"]);
            $study->setRebilledFee($rebilledFee);
        }

        if (isset($fields["ecoparticipationFee"])) {
            $ecoparticipationFee = Validator::requiredInt($fields["ecoparticipationFee"]);
            $study->setEcoparticipationFee($ecoparticipationFee);
        }

        if (isset($fields["outsourcingFee"])) {
            $outsourcingFee = Validator::requiredInt($fields["outsourcingFee"]);
            $study->setOutsourcingFee($outsourcingFee);
        }

        if (isset($fields["archivedDate"])) {
            $archivedDate = Validator::requiredDate($fields["archivedDate"]);
            $study->setArchivedDate($archivedDate);
        }

        if (isset($fields["firmId"])) {
            $firmId = Validator::requiredId($fields["firmId"]);
            $firm = $this->firmService->getOne($firmId);
            $study->setFirm($firm);
        }

        if (isset($fields["contactIds"])) {
            $contactIds = $fields["contactIds"];
            $contacts = $this->contactService->getSome($contactIds);
            $study->setContacts($contacts);
        }

        if (isset($fields["leaderIds"])) {
            $leaderIds = $fields["leaderIds"];
            $leaders = $this->memberService->getSome($leaderIds);
            $study->setLeaders($leaders);
        }

        if (isset($fields["consultantIds"])) {
            $consultantIds = $fields["consultantIds"];
            $consultants = $this->memberService->getSome($consultantIds);
            $study->setConsultants($consultants);
        }

        if (isset($fields["qualityManagerIds"])) {
            $qualityManagerIds = $fields["qualityManagerIds"];
            $qualityManagers = $this->memberService->getSome($qualityManagerIds);
            $study->setQualityManagers($qualityManagers);
        }

        $this->studyDataService->persist($study);

        return $study;
    }

}