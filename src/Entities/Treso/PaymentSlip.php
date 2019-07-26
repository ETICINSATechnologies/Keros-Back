<?php

namespace Keros\Entities\Treso;

use Keros\Entities\Core\Address;
use Keros\Entities\Core\Member;
use Keros\Entities\Ua\Study;

/**
 * @Entity
 * @Table(name="treso_payment_slip")
 */
class PaymentSlip implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=32) */
    protected $missionRecapNumber;

    /** @Column(type="string", length=255) */
    protected $consultantName;

    /** @Column(type="string", length=255) */
    protected $consultantSocialSecurityNumber;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Address")
     * @JoinColumn(name="addressId", referencedColumnName="id")
     **/
    protected $address;

    /** @Column(type="string", length=255) */
    protected $email;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Ua\Study", inversedBy = "paymentSlips")
     * @JoinColumn(name="studyId", referencedColumnName="id")
     **/
    protected $study;

    /** @Column(type="string", length=255) */
    protected $clientName;

    /** @Column(type="string", length=255) */
    protected $projectLead;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="consultantId", referencedColumnName="id")
     **/
    protected $consultant;

    /** @Column(type="boolean") */
    protected $isTotalJeh;

    /** @Column(type="boolean") */
    protected $isStudyPaid;

    /** @Column(type="string", length=2048) */
    protected $amountDescription;

    /** @Column(type="datetime") */
    protected $createdDate;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="creatorId", referencedColumnName="id")
     **/
    protected $createdBy;

    /** @Column(type="boolean") */
    protected $validatedByUa;

    /** @Column(type="datetime") */
    protected $validatedByUaDate;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="uaValidatorId", referencedColumnName="id")
     **/
    protected $validatedByUaMember;

    /** @Column(type="boolean") */
    protected $validatedByPerf;

    /** @Column(type="datetime") */
    protected $validatedByPerfDate;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="perfValidatorId", referencedColumnName="id")
     **/
    protected $validatedByPerfMember;

    /**
     * PaymentSlip constructor.
     * @param $missionRecapNumber
     * @param $consultantName
     * @param $consultantSocialSecurityNumber
     * @param $address
     * @param $email
     * @param $study
     * @param $clientName
     * @param $projectLead
     * @param $consultant
     * @param $isTotalJeh
     * @param $isStudyPaid
     * @param $amountDescription
     * @param $createdDate
     * @param $createdBy
     * @param $validatedByUa
     * @param $validatedByUaDate
     * @param $validatedByUaMember
     * @param $validatedByPerf
     * @param $validatedByPerfDate
     * @param $validatedByPerfMember
     */
    public function __construct($missionRecapNumber, $consultantName, $consultantSocialSecurityNumber, $address, $email, $study, $clientName, $projectLead, $consultant, $isTotalJeh, $isStudyPaid, $amountDescription, $createdDate, $createdBy, $validatedByUa, $validatedByUaDate, $validatedByUaMember, $validatedByPerf, $validatedByPerfDate, $validatedByPerfMember)
    {
        $this->missionRecapNumber = $missionRecapNumber;
        $this->consultantName = $consultantName;
        $this->consultantSocialSecurityNumber = $consultantSocialSecurityNumber;
        $this->address = $address;
        $this->email = $email;
        $this->study = $study;
        $this->clientName = $clientName;
        $this->projectLead = $projectLead;
        $this->consultant = $consultant;
        $this->isTotalJeh = $isTotalJeh;
        $this->isStudyPaid = $isStudyPaid;
        $this->amountDescription = $amountDescription;
        $this->createdDate = $createdDate;
        $this->createdBy = $createdBy;
        $this->validatedByUa = $validatedByUa;
        $this->validatedByUaDate = $validatedByUaDate;
        $this->validatedByUaMember = $validatedByUaMember;
        $this->validatedByPerf = $validatedByPerf;
        $this->validatedByPerfDate = $validatedByPerfDate;
        $this->validatedByPerfMember = $validatedByPerfMember;
    }

    public function jsonSerialize()
    {
        return [
            'missionRecapNumber' => $this->getMissionRecapNumber(),
            'consultantName' => $this->getConsultantName(),
            'consultantSocialSecurityNumber' => $this->getConsultantSocialSecurityNumber(),
            'address' => $this->getAddress(),
            'email' => $this->getEmail(),
            'study' => $this->getStudy(),
            'clientName' => $this->getClientName(),
            'projectLead' => $this->getProjectLead(),
            'consultant' => array(
                'consultantId' => $this->getConsultant()->getId(),
                'firstName' => $this->getConsultant()->getFirstName(),
                'lastName' => $this->getConsultant()->getLastName(),
            ),
            'isTotalJeh' => $this->getisTotalJeh(),
            'isStudyPaid' => $this->getisStudyPaid(),
            'amountDescription' => $this->getAmountDescription(),
            'createdDate' => $this->getCreatedDate(),
            'createdBy' => $this->getCreatedBy(),
            'validatedByUa' => $this->getValidatedByUa(),
            'validatedByUaDate' => $this->getValidatedByUaDate(),
            'validatedByUaMember' => $this->getValidatedByUaMember(),
            'validatedByPerf' => $this->getValidatedByPerf(),
            'validatedByPerfDate' => $this->getValidatedByPerfDate(),
            'validatedByPerfMember' => $this->getValidatedByPerfMember(),
        ];
    }

    public static function getSearchFields(): array {
        return ['missionRecapNumber', 'consultantName'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMissionRecapNumber()
    {
        return $this->missionRecapNumber;
    }

    /**
     * @param string $missionRecapNumber
     */
    public function setMissionRecapNumber($missionRecapNumber): void
    {
        $this->missionRecapNumber = $missionRecapNumber;
    }

    /**
     * @return string
     */
    public function getConsultantName()
    {
        return $this->consultantName;
    }

    /**
     * @param string $consultantName
     */
    public function setConsultantName($consultantName): void
    {
        $this->consultantName = $consultantName;
    }

    /**
     * @return mixed
     */
    public function getConsultantSocialSecurityNumber()
    {
        return $this->consultantSocialSecurityNumber;
    }

    /**
     * @param string $consultantSocialSecurityNumber
     */
    public function setConsultantSocialSecurityNumber($consultantSocialSecurityNumber): void
    {
        $this->consultantSocialSecurityNumber = $consultantSocialSecurityNumber;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return Study
     */
    public function getStudy()
    {
        return $this->study;
    }

    /**
     * @param Study $study
     */
    public function setStudy($study): void
    {
        $this->study = $study;
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param string $clientName
     */
    public function setClientName($clientName): void
    {
        $this->clientName = $clientName;
    }

    /**
     * @return string
     */
    public function getProjectLead()
    {
        return $this->projectLead;
    }

    /**
     * @param string $projectLead
     */
    public function setProjectLead($projectLead): void
    {
        $this->projectLead = $projectLead;
    }

    /**
     * @return Member
     */
    public function getConsultant()
    {
        return $this->consultant;
    }

    /**
     * @param Member $consultant
     */
    public function setConsultant($consultant): void
    {
        $this->consultant = $consultant;
    }

    /**
     * @return boolean
     */
    public function getisTotalJeh()
    {
        return $this->isTotalJeh;
    }

    /**
     * @param boolean $isTotalJeh
     */
    public function setIsTotalJeh($isTotalJeh): void
    {
        $this->isTotalJeh = $isTotalJeh;
    }

    /**
     * @return boolean
     */
    public function getisStudyPaid()
    {
        return $this->isStudyPaid;
    }

    /**
     * @param boolean $isStudyPaid
     */
    public function setIsStudyPaid($isStudyPaid): void
    {
        $this->isStudyPaid = $isStudyPaid;
    }

    /**
     * @return mixed
     */
    public function getAmountDescription()
    {
        return $this->amountDescription;
    }

    /**
     * @param string $amountDescription
     */
    public function setAmountDescription($amountDescription): void
    {
        $this->amountDescription = $amountDescription;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate($createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return Member
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param Member $createdBy
     */
    public function setCreatedBy($createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return boolean
     */
    public function getValidatedByUa()
    {
        return $this->validatedByUa;
    }

    /**
     * @param boolean $validatedByUa
     */
    public function setValidatedByUa($validatedByUa): void
    {
        $this->validatedByUa = $validatedByUa;
    }

    /**
     * @return \DateTime
     */
    public function getValidatedByUaDate()
    {
        return $this->validatedByUaDate;
    }

    /**
     * @param \DateTime $validatedByUaDate
     */
    public function setValidatedByUaDate($validatedByUaDate): void
    {
        $this->validatedByUaDate = $validatedByUaDate;
    }

    /**
     * @return Member
     */
    public function getValidatedByUaMember()
    {
        return $this->validatedByUaMember;
    }

    /**
     * @param Member $validatedByUaMember
     */
    public function setValidatedByUaMember($validatedByUaMember): void
    {
        $this->validatedByUaMember = $validatedByUaMember;
    }

    /**
     * @return boolean
     */
    public function getValidatedByPerf()
    {
        return $this->validatedByPerf;
    }

    /**
     * @param boolean $validatedByPerf
     */
    public function setValidatedByPerf($validatedByPerf): void
    {
        $this->validatedByPerf = $validatedByPerf;
    }

    /**
     * @return \DateTime
     */
    public function getValidatedByPerfDate()
    {
        return $this->validatedByPerfDate;
    }

    /**
     * @param \DateTime $validatedByPerfDate
     */
    public function setValidatedByPerfDate($validatedByPerfDate): void
    {
        $this->validatedByPerfDate = $validatedByPerfDate;
    }

    /**
     * @return Member
     */
    public function getValidatedByPerfMember()
    {
        return $this->validatedByPerfMember;
    }

    /**
     * @param Member $validatedByPerfMember
     */
    public function setValidatedByPerfMember($validatedByPerfMember): void
    {
        $this->validatedByPerfMember = $validatedByPerfMember;
    }


}