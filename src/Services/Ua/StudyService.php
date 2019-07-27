<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\StudyDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\ConsultantService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\PositionService;
use Keros\Services\Core\UserService;
use Keros\Tools\Validator;
use Monolog\Logger;
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
     * @var ProvenanceService
     */
    private $provenanceService;
    /**
     * @var StudyDataService
     */
    private $studyDataService;
    /**
     * @var ConsultantService
     */
    private $consultantService;

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
        $this->studyDataService = $container->get(StudyDataService::class);
        $this->consultantService = $container->get(ConsultantService::class);
    }

    /**
     * @param array $fields
     * @return Study
     * @throws KerosException
     */
    public function create(array $fields): Study
    {
        $name = Validator::requiredString($fields["name"]);
        $description = Validator::optionalString(isset($fields["description"]) ? $fields["description"] : null);

        $statusId = Validator::optionalId(isset($fields["statusId"]) ? $fields["statusId"] : null);
        if ($statusId != null)
            $status = $this->statusService->getOne($statusId);
        else
            $status = null;

        $firmId = Validator::optionalId(isset($fields["firmId"]) ? $fields["firmId"] : null);
        if ($firmId != null)
            $firm = $this->firmService->getOne($firmId);
        else
            $firm = null;

        $signDate = Validator::optionalDate(isset($fields["signDate"]) ? $fields["signDate"] : null);

        $endDate = Validator::optionalDate(isset($fields["endDate"]) ? $fields["endDate"] : null);

        $managementFee = Validator::optionalFloat(isset($fields["managementFee"]) ? $fields["managementFee"] : null);
        $realizationFee = Validator::optionalFloat(isset($fields["realizationFee"]) ? $fields["realizationFee"] : null);
        $rebilledFee = Validator::optionalFloat(isset($fields["rebilledFee"]) ? $fields["rebilledFee"] : null);
        $ecoparticipationFee = Validator::optionalFloat(isset($fields["ecoparticipationFee"]) ? $fields["ecoparticipationFee"] : null);
        $outsourcingFee = Validator::optionalFloat(isset($fields["outsourcingFee"]) ? $fields["outsourcingFee"] : null);

        $archivedDate = Validator::optionalDate(isset($fields["archivedDate"]) ? $fields["archivedDate"] : null);

        if (isset($fields["contactIds"])) {
            $contactIds = $fields["contactIds"];
            $contacts = $this->contactService->getSome($contactIds);
        } else
            $contacts = array();

        if (isset($fields["leaderIds"])) {
            $leaderIds = $fields["leaderIds"];
            $leaders = $this->memberService->getSome($leaderIds);
        } else
            $leaders = array();

        if (isset($fields["consultantIds"])) {
            $consultantIds = $fields["consultantIds"];
            $consultants = $this->consultantService->getSome($consultantIds);
        } else
            $consultants = array();

        if (isset($fields["qualityManagerIds"])) {
            $qualityManagerIds = $fields["qualityManagerIds"];
            $qualityManagers = $this->memberService->getSome($qualityManagerIds);
        } else
            $qualityManagers = array();

        $provenance = null;
        $provenanceId = Validator::optionalId(isset($fields["provenanceId"]) ? $fields["provenanceId"] : null);
        if (isset($provenanceId)) {
            $provenance = $this->provenanceService->getOne($provenanceId);
        }

        $confidential = Validator::optionalBool(isset($fields["confidential"]) ? $fields["confidential"] : null);

        $mainLeader = Validator::optionalInt(isset($fields["mainLeader"]) ? $fields["mainLeader"] : null);
        $mainQualityManager = Validator::optionalInt(isset($fields["mainQualityManager"]) ? $fields["mainQualityManager"] : null);
        $mainConsultant = Validator::optionalInt(isset($fields["mainConsultant"]) ? $fields["mainConsultant"] : null);

        $fieldId = Validator::optionalId(isset($fields["fieldId"]) ? $fields["fieldId"] : null);
        if ($fieldId != null)
            $field = $this->fieldService->getOne($fieldId);
        else
            $field = null;

        $study = new Study($name, $description, $field, $status, $firm, $contacts, $leaders, $consultants, $qualityManagers, $confidential, $mainLeader, $mainQualityManager, $mainConsultant);

        $study->setProvenance($provenance);
        $study->setSignDate($signDate);
        $study->setEndDate($endDate);
        $study->setManagementFee($managementFee);
        $study->setRealizationFee($realizationFee);
        $study->setRebilledFee($rebilledFee);
        $study->setEcoparticipationFee($ecoparticipationFee);
        $study->setOutsourcingFee($outsourcingFee);
        $study->setArchivedDate($archivedDate);
        $study->setConfidential($confidential);
        $study->setMainLeader($mainLeader);
        $study->setMainConsultant($mainConsultant);
        $study->setMainQualityManager($mainQualityManager);

        $this->studyDataService->persist($study);
        return $study;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $study = $this->getOne($id);
        //$this->paymentSlipService->deletePaymentSlipsRelatedToStudy($id);

        $this->studyDataService->delete($study);
    }

    /**
     * @param int $idFirm
     * @throws KerosException
     */
    public function deleteStudiesRelatedtoFirm(int $idFirm): void
    {
        $studies = $this->getAll();
        foreach ($studies as $study) {
            $study = Validator::requiredStudy($study);
            if ($study->getFirm()->getId() == $idFirm) {
                $this->studyDataService->delete($study);
            }
        }
    }

    /**
     * @param int $id
     * @return Study
     * @throws KerosException
     */
    public function getOne(int $id): Study
    {
        $id = Validator::requiredId($id);

        $study = $this->studyDataService->getOne($id);
        if (!$study) {
            throw new KerosException("The study could not be found", 404);
        }
        return $study;
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->studyDataService->getAll();
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->studyDataService->getPage($requestParameters);
    }

    /**
     * @param RequestParameters $requestParameters
     * @return int
     */
    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->studyDataService->getCount($requestParameters);
    }

    /**
     * @param int $id
     * @param array|null $fields
     * @return Study
     * @throws KerosException
     */
    public function update(int $id, ?array $fields): Study
    {

        $id = Validator::requiredId($id);
        $study = $this->getOne($id);

        $name = Validator::requiredString($fields["name"]);
        $description = Validator::optionalString(isset($fields["description"]) ? $fields["description"] : null);

        $statusId = Validator::optionalId(isset($fields["statusId"]) ? $fields["statusId"] : null);
        if ($statusId != null)
            $status = $this->statusService->getOne($statusId);
        else
            $status = null;

        $firmId = Validator::optionalId(isset($fields["firmId"]) ? $fields["firmId"] : null);
        if ($firmId != null)
            $firm = $this->firmService->getOne($firmId);
        else
            $firm = null;

        $signDate = Validator::optionalDate(isset($fields["signDate"]) ? $fields["signDate"] : null);

        $endDate = Validator::optionalDate(isset($fields["endDate"]) ? $fields["endDate"] : null);

        $managementFee = Validator::optionalFloat(isset($fields["managementFee"]) ? $fields["managementFee"] : null);
        $realizationFee = Validator::optionalFloat(isset($fields["realizationFee"]) ? $fields["realizationFee"] : null);
        $rebilledFee = Validator::optionalFloat(isset($fields["rebilledFee"]) ? $fields["rebilledFee"] : null);
        $ecoparticipationFee = Validator::optionalFloat(isset($fields["ecoparticipationFee"]) ? $fields["ecoparticipationFee"] : null);
        $outsourcingFee = Validator::optionalFloat(isset($fields["outsourcingFee"]) ? $fields["outsourcingFee"] : null);

        $archivedDate = Validator::optionalDate(isset($fields["archivedDate"]) ? $fields["archivedDate"] : null);

        if (isset($fields["contactIds"])) {
            $contactIds = $fields["contactIds"];
            $contacts = $this->contactService->getSome($contactIds);
        } else
            $contacts = array();

        if (isset($fields["leaderIds"])) {
            $leaderIds = $fields["leaderIds"];
            $leaders = $this->memberService->getSome($leaderIds);
        } else
            $leaders = array();

        if (isset($fields["consultantIds"])) {
            $consultantIds = $fields["consultantIds"];
            $consultants = $this->consultantService->getSome($consultantIds);
        } else
            $consultants = array();

        if (isset($fields["qualityManagerIds"])) {
            $qualityManagerIds = $fields["qualityManagerIds"];
            $qualityManagers = $this->memberService->getSome($qualityManagerIds);
        } else
            $qualityManagers = array();

        $provenance = null;
        $provenanceId = Validator::optionalId(isset($fields["provenanceId"]) ? $fields["provenanceId"] : null);
        if (isset($provenanceId)) {
            $provenance = $this->provenanceService->getOne($provenanceId);
        }

        $fieldId = Validator::optionalId(isset($fields["fieldId"]) ? $fields["fieldId"] : null);
        if ($fieldId != null)
            $field = $this->fieldService->getOne($fieldId);
        else
            $field = null;

        $confidential = Validator::optionalBool(isset($fields["confidential"]) ? $fields["confidential"] : null);

        $mainLeader = Validator::optionalInt(isset($fields["mainLeader"]) ? $fields["mainLeader"] : null);
        $mainQualityManager = Validator::optionalInt(isset($fields["mainQualityManager"]) ? $fields["mainQualityManager"] : null);
        $mainConsultant = Validator::optionalInt(isset($fields["mainConsultant"]) ? $fields["mainConsultant"] : null);

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
        $study->setConfidential($confidential);
        $study->setMainLeader($mainLeader);
        $study->setMainConsultant($mainConsultant);
        $study->setMainQualityManager($mainQualityManager);

        $this->studyDataService->persist($study);

        return $study;
    }


    /**
     * @param int $id
     * @return bool
     * @throws KerosException
     */
    public function consultantsAreValid(int $id): bool
    {
        $id = Validator::requiredId($id);
        $study = $this->getOne($id);
        if (empty($study->getConsultantsArray())) {
            return false;
        }
        return true;
    }
}