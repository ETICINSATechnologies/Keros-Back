<?php

namespace Keros\Entities\Ua;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="ua_study")
 */
class Study implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="integer") */
    protected $number;

    /** @Column(type="string", length=100) */
    protected $name;

    /** @Column(type="string", length=255) */
    protected $description;

    /**
     * @ManyToOne(targetEntity="Field")
     * @JoinColumn(name="FieldId", referencedColumnName="id")
     **/
    protected $field;

    /**
     * @ManyToOne(targetEntity="Provenance")
     * @JoinColumn(name="ProvenanceId", referencedColumnName="id")
     **/
    protected $provenance;

    /**
     * @ManyToOne(targetEntity="Status")
     * @JoinColumn(name="StatusId", referencedColumnName="id")
     **/
    protected $status;

    /** @Column(type="datetime") */
    protected $signDate;

    /** @Column(type="datetime") */
    protected $endDate;

    /** @Column(type="integer") */
    protected $managementFee;

    /** @Column(type="integer") */
    protected $realizationFee;

    /** @Column(type="integer") */
    protected $rebilledFee;

    /** @Column(type="integer") */
    protected $ecoParticipationFee;

    /** @Column(type="integer") */
    protected $outsourcingFee;

    /** @Column(type="datetime") */
    protected $archivedDate;

    /**
     * @ManyToOne(targetEntity="Firm")
     * @JoinColumn(name="FirmId", referencedColumnName="id")
     **/
    protected $firm;

    /**
     * @ManyToMany(targetEntity="Contact")
     * @JoinTable(name="ua_study_contact",
     *      joinColumns={@JoinColumn(name="studyId", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="contactId", referencedColumnName="id")}
     *      )
     */
    protected $contacts;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Core\Member")
     * @JoinTable(name="ua_study_leader",
     *      joinColumns={@JoinColumn(name="studyId", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="memberId", referencedColumnName="id")}
     *      )
     */
    protected $leaders;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Core\Member")
     * @JoinTable(name="ua_study_qualitymanager",
     *      joinColumns={@JoinColumn(name="studyId", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="memberId", referencedColumnName="id")}
     *      )
     */
    protected $qualityManagers;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Core\Member")
     * @JoinTable(name="ua_study_consultant",
     *      joinColumns={@JoinColumn(name="studyId", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="memberId", referencedColumnName="id")}
     *      )
     */
    protected $consultants;

    /**
     * Study constructor.
     * @param $number
     * @param $name
     * @param $description
     * @param $field
     * @param $status
     * @param $firm
     * @param $contacts
     * @param $leaders
     * @param $qualityManagers
     * @param $consultants
     */
    public function __construct($number, $name, $description, $field, $status, $firm, $contacts, $leaders, $qualityManagers, $consultants)
    {
        $this->number = $number;
        $this->name = $name;
        $this->description = $description;
        $this->field = $field;
        $this->status = $status;
        $this->firm = $firm;
        $this->contacts = $contacts;
        $this->leaders = $leaders;
        $this->qualityManagers = $qualityManagers;
        $this->consultants = $consultants;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'number' => $this->getNumber(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'field' => $this->getField(),
            'status' => $this->getStatus(),
            'provenance' => $this->getProvenance(),
            'signDate' => $this->getSignDate()->format('Y-m-d'),
            'endDate' => $this->getEndDate()->format('Y-m-d'),
            'managementFee' => $this->getManagementFee(),
            'realizationFee' => $this->getRealizationFee(),
            'rebilledFee' => $this->getRebilledFee(),
            'ecoparticipationFee' => $this->getEcoparticipationFee(),
            'outsourcingFee' => $this->getOutsourcingFee(),
            'archivedDate' => $this->getArchivedDate()->format('Y-m-d'),
            'firm' => $this->getFirm(),
            'contacts' => $this->getContactsArray(),
            'leaders' => $this->getLeadersArray(),
            'consultants' => $this->getConsultantsArray(),
            'qualityManagers' => $this->getQualityManagersArray()
        ];
    }

    public static function getSearchFields(): array {
        return ['number', 'name'];
    }

    // Getters and setters
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getProvenance()
    {
        return $this->provenance;
    }

    /**
     * @param mixed $provenance
     */
    public function setProvenance($provenance): void
    {
        $this->provenance = $provenance;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSignDate()
    {
        return $this->signDate;
    }

    /**
     * @param mixed $signDate
     */
    public function setSignDate($signDate): void
    {
        $this->signDate = $signDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getManagementFee()
    {
        return $this->managementFee;
    }

    /**
     * @param mixed $managementFee
     */
    public function setManagementFee($managementFee): void
    {
        $this->managementFee = $managementFee;
    }

    /**
     * @return mixed
     */
    public function getRealizationFee()
    {
        return $this->realizationFee;
    }

    /**
     * @param mixed $realizationFee
     */
    public function setRealizationFee($realizationFee): void
    {
        $this->realizationFee = $realizationFee;
    }

    /**
     * @return mixed
     */
    public function getRebilledFee()
    {
        return $this->rebilledFee;
    }

    /**
     * @param mixed $rebilledFee
     */
    public function setRebilledFee($rebilledFee): void
    {
        $this->rebilledFee = $rebilledFee;
    }

    /**
     * @return mixed
     */
    public function getEcoParticipationFee()
    {
        return $this->ecoParticipationFee;
    }

    /**
     * @param mixed $ecoParticipationFee
     */
    public function setEcoParticipationFee($ecoParticipationFee): void
    {
        $this->ecoParticipationFee = $ecoParticipationFee;
    }

    /**
     * @return mixed
     */
    public function getOutsourcingFee()
    {
        return $this->outsourcingFee;
    }

    /**
     * @param mixed $outsourcingFee
     */
    public function setOutsourcingFee($outsourcingFee): void
    {
        $this->outsourcingFee = $outsourcingFee;
    }

    /**
     * @return mixed
     */
    public function getArchivedDate()
    {
        return $this->archivedDate;
    }

    /**
     * @param mixed $archivedDate
     */
    public function setArchivedDate($archivedDate): void
    {
        $this->archivedDate = $archivedDate;
    }

    /**
     * @return mixed
     */
    public function getFirm()
    {
        return $this->firm;
    }

    /**
     * @param mixed $firm
     */
    public function setFirm($firm): void
    {
        $this->firm = $firm;
    }

    /**
     * @return mixed
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @return array
     */
    public function getContactsArray()
    {
        $contacts = [];
        foreach ($this->getContacts() as $contact)
        {
            $contacts[] = $contact;
        }

        return $contacts;
    }

    /**
     * @param mixed $contacts
     */
    public function setContacts($contacts): void
    {
        $this->contacts = $contacts;
    }

    /**
     * @return mixed
     */
    public function getLeaders()
    {
        return $this->leaders;
    }

    /**
     * @return array
     */
    public function getLeadersArray()
    {
        $leaders = [];
        foreach ($this->getLeaders() as $leader)
        {
            $leaders[] = $leader;
        }

        return $leaders;
    }

    /**
     * @param mixed $leaders
     */
    public function setLeaders($leaders): void
    {
        $this->leaders = $leaders;
    }

    /**
     * @return mixed
     */
    public function getQualityManagers()
    {
        return $this->qualityManagers;
    }

    /**
     * @return array
     */
    public function getQualityManagersArray()
    {
        $qualityManagers = [];
        foreach ($this->getQualityManagers() as $qualityManager)
        {
            $qualityManagers[] = $qualityManager;
        }

        return $qualityManagers;
    }

    /**
     * @param mixed $qualityManagers
     */
    public function setQualityManagers($qualityManagers): void
    {
        $this->qualityManagers = $qualityManagers;
    }

    /**
     * @return mixed
     */
    public function getConsultants()
    {
        return $this->consultants;
    }

    /**
     * @return array
     */
    public function getConsultantsArray()
    {
        $consultants = [];
        foreach ($this->getConsultants() as $consultant)
        {
            $consultants[] = $consultant;
        }

        return $consultants;
    }

    /**
     * @param mixed $consultants
     */
    public function setConsultants($consultants): void
    {
        $this->consultants = $consultants;
    }
}