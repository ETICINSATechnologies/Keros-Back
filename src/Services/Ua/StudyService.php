<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\StudyDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
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
        $projectNumber = Validator::requiredInt($fields["projectNumber"]);
        $name = Validator::requiredString($fields["name"]);
        $description = Validator::optionalString(isset($fields["description"]) ? $fields["description"] : null);

        $fieldId = Validator::requiredId($fields["fieldId"]);
        $field = $this->fieldService->getOne($fieldId);

        $statusId = Validator::requiredId($fields["statusId"]);
        $status = $this->statusService->getOne($statusId);

        $firmId = Validator::requiredId($fields["firmId"]);
        $firm = $this->firmService->getOne($firmId);

        $signDate = Validator::optionalDate(isset($fields["signDate"]) ? $fields["signDate"] : null);

        $endDate = Validator::optionalDate(isset($fields["endDate"]) ? $fields["endDate"] : null);

        $managementFee = Validator::optionalFloat(isset($fields["managementFee"]) ? $fields["managementFee"] : null);
        $realizationFee = Validator::optionalFloat(isset($fields["realizationFee"]) ? $fields["realizationFee"] : null);
        $rebilledFee = Validator::optionalFloat(isset($fields["rebilledFee"]) ? $fields["rebilledFee"] : null);
        $ecoparticipationFee = Validator::optionalFloat(isset($fields["ecoparticipationFee"]) ? $fields["ecoparticipationFee"] : null);
        $outsourcingFee = Validator::optionalFloat(isset($fields["outsourcingFee"]) ? $fields["outsourcingFee"] : null);

        $archivedDate = Validator::optionalDate(isset($fields["archivedDate"]) ? $fields["archivedDate"] : null);

        $contactIds = $fields["contactIds"];
        $contacts = $this->contactService->getSome($contactIds);

        $leaderIds = $fields["leaderIds"];
        $leaders = $this->memberService->getSome($leaderIds);

        $consultantIds = $fields["consultantIds"];
        $consultants = $this->memberService->getSome($consultantIds);

        $qualityManagerIds = $fields["qualityManagerIds"];
        $qualityManagers = $this->memberService->getSome($qualityManagerIds);

        $provenance = null;
        $provenanceId = Validator::optionalId(isset($fields["provenanceId"]) ? $fields["provenanceId"] : null);
        if (isset($provenanceId)) {
            $provenance = $this->provenanceService->getOne($provenanceId);
        }

        $study = new Study($projectNumber, $name, $description, $field, $status, $firm, $contacts, $leaders, $consultants, $qualityManagers);
        $study->setProvenance($provenance);
        $study->setSignDate($signDate);
        $study->setEndDate($endDate);
        $study->setManagementFee($managementFee);
        $study->setRealizationFee($realizationFee);
        $study->setRebilledFee($rebilledFee);
        $study->setEcoparticipationFee($ecoparticipationFee);
        $study->setOutsourcingFee($outsourcingFee);
        $study->setArchivedDate($archivedDate);

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

        $projectNumber = Validator::requiredInt($fields["projectNumber"]);
        $name = Validator::requiredString($fields["name"]);
        $description = Validator::optionalString(isset($fields["description"]) ? $fields["description"] : null);

        $fieldId = Validator::requiredId($fields["fieldId"]);
        $field = $this->fieldService->getOne($fieldId);

        $statusId = Validator::requiredId($fields["statusId"]);
        $status = $this->statusService->getOne($statusId);

        $firmId = Validator::requiredId($fields["firmId"]);
        $firm = $this->firmService->getOne($firmId);

        $signDate = Validator::optionalDate(isset($fields["signDate"]) ? $fields["signDate"] : null);

        $endDate = Validator::optionalDate(isset($fields["endDate"]) ? $fields["endDate"] : null);

        $managementFee = Validator::optionalFloat(isset($fields["managementFee"]) ? $fields["managementFee"] : null);
        $realizationFee = Validator::optionalFloat(isset($fields["realizationFee"]) ? $fields["realizationFee"] : null);
        $rebilledFee = Validator::optionalFloat(isset($fields["rebilledFee"]) ? $fields["rebilledFee"] : null);
        $ecoparticipationFee = Validator::optionalFloat(isset($fields["ecoparticipationFee"]) ? $fields["ecoparticipationFee"] : null);
        $outsourcingFee = Validator::optionalFloat(isset($fields["outsourcingFee"]) ? $fields["outsourcingFee"] : null);

        $archivedDate = Validator::optionalDate(isset($fields["archivedDate"]) ? $fields["archivedDate"] : null);

        $contactIds = $fields["contactIds"];
        $contacts = $this->contactService->getSome($contactIds);

        $leaderIds = $fields["leaderIds"];
        $leaders = $this->memberService->getSome($leaderIds);

        $consultantIds = $fields["consultantIds"];
        $consultants = $this->memberService->getSome($consultantIds);

        $qualityManagerIds = $fields["qualityManagerIds"];
        $qualityManagers = $this->memberService->getSome($qualityManagerIds);

        $provenance = null;
        $provenanceId = Validator::optionalId(isset($fields["provenanceId"]) ? $fields["provenanceId"] : null);
        if (isset($provenanceId)) {
            $provenance = $this->provenanceService->getOne($provenanceId);
        }

        $study->setProjectNumber($projectNumber);
        $study->setName($name);
        $study->setDescription($description);
        $study->setField($field);
        $study->setStatus($status);
        $study->setFirm($firm);
        $study->setContacts($contacts);
        $study->setLeaders($leaders);
        $study->setConsultants($consultants);
        $study->setQualityManagers($qualityManagers);
        $study->setProvenance($provenance);
        $study->setSignDate($signDate);
        $study->setEndDate($endDate);
        $study->setManagementFee($managementFee);
        $study->setRealizationFee($realizationFee);
        $study->setRebilledFee($rebilledFee);
        $study->setEcoparticipationFee($ecoparticipationFee);
        $study->setOutsourcingFee($outsourcingFee);
        $study->setArchivedDate($archivedDate);


        $this->studyDataService->persist($study);

        return $study;
    }

}