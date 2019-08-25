<?php

namespace Keros\Entities\Treso;

use Keros\Entities\Core\Address;
use Keros\Entities\Core\Member;
use Keros\Entities\Ua\Study;

/**
 * @Entity
 * @Table(name="treso_facture")
 */
class Facture implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=32) */
    protected $numero;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Address")
     * @JoinColumn(name="addressId", referencedColumnName="id")
     **/
    protected $fullAddress;

    /** @Column(type="string", length=255) */
    protected $clientName;

    /** @Column(type="string", length=255) */
    protected $contactName;

    /** @Column(type="string", length=255) */
    protected $contactEmail;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Ua\Study")
     * @JoinColumn(name="studyId", referencedColumnName="id")
     **/
    protected $study;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Treso\FactureType")
     * @JoinColumn(name="typeId", referencedColumnName="id")
     **/
    protected $type;

    /** @Column(type="string", length=2048) */
    protected $amountDescription;

    /** @Column(type="string", length=255) */
    protected $subject;

    /** @Column(type="datetime") */
    protected $agreementSignDate;

    /** @Column(type="float") */
    protected $amountHT;

    /** @Column(type="float") */
    protected $taxPercentage;

    /** @Column(type="datetime") */
    protected $dueDate;

    /** @Column(type="string", length=2048) */
    protected $additionalInformation;

    /** @Column(type="datetime") */
    protected $createdDate;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="createdById", referencedColumnName="id")
     **/
    protected $createdBy;

    /** @Column(type="boolean") */
    protected $validatedByUa;

    /** @Column(type="datetime") */
    protected $validatedByUaDate;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="validatedByUaMemberId", referencedColumnName="id")
     **/
    protected $validatedByUaMember;

    /** @Column(type="boolean") */
    protected $validatedByPerf;

    /** @Column(type="datetime") */
    protected $validatedByPerfDate;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member")
     * @JoinColumn(name="validatedByPerfMemberId", referencedColumnName="id")
     **/
    protected $validatedByPerfMember;

    /**
     * @var FactureDocument[]
     * @OneToMany(targetEntity="FactureDocument", mappedBy="facture")
     */
    private $relatedDocuments;

    /**
     * Facture constructor.
     * @param $numero
     * @param $fullAddress
     * @param $clientName
     * @param $contactName
     * @param $contactEmail
     * @param $study
     * @param $type
     * @param $amountDescription
     * @param $subject
     * @param $agreementSignDate
     * @param $amountHT
     * @param $taxPercentage
     * @param $dueDate
     * @param $additionalInformation
     * @param $createdDate
     * @param $createdBy
     * @param $validatedByUa
     * @param $validatedByUaDate
     * @param $validatedByUaMember
     * @param $validatedByPerf
     * @param $validatedByPerfDate
     * @param $validatedByPerfMember
     */
    public function __construct($numero, $fullAddress, $clientName, $contactName, $contactEmail, $study, $type, $amountDescription, $subject, $agreementSignDate, $amountHT, $taxPercentage, $dueDate, $additionalInformation, $createdDate, $createdBy, $validatedByUa, $validatedByUaDate, $validatedByUaMember, $validatedByPerf, $validatedByPerfDate, $validatedByPerfMember)
    {
        $this->numero = $numero;
        $this->fullAddress = $fullAddress;
        $this->clientName = $clientName;
        $this->contactName = $contactName;
        $this->contactEmail = $contactEmail;
        $this->study = $study;
        $this->type = $type;
        $this->amountDescription = $amountDescription;
        $this->subject = $subject;
        $this->agreementSignDate = $agreementSignDate;
        $this->amountHT = $amountHT;
        $this->taxPercentage = $taxPercentage;
        $this->dueDate = $dueDate;
        $this->additionalInformation = $additionalInformation;
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
            'id' => $this->getId(),
            'numero' => $this->getNumero(),
            'fullAddress' => $this->getFullAddress(),
            'clientName' => $this->getClientName(),
            'contactName' => $this->getContactName(),
            'contactEmail' => $this->getContactEmail(),
            'study' => $this->getStudy(),
            'type' => $this->getType()->getLabel(),
            'amountDescription' => $this->getAmountDescription(),
            'subject' => $this->getSubject(),
            'agreementSignDate' => $this->getAgreementSignDateFormatted(),
            'amountHT' => $this->getAmountHT(),
            'taxPercentage' => $this->getTaxPercentage(),
            'amountTTC' => $this->getAmountTTC(),
            'dueDate' => $this->getDueDateFormatted(),
            'additionalInformation' => $this->getAdditionalInformation(),
            'createdDate' => $this->getCreatedDateFormatted(),
            'createdBy' => $this->getCreatedBy(),
            'validatedByUa' => $this->getValidatedByUa(),
            'validatedByUaDate' => $this->getValidatedByUaDateFormatted(),
            'validatedByUaMember' => $this->getValidatedByUaMember(),
            'validatedByPerf' => $this->getValidatedByPerf(),
            'validatedByPerfDate' => $this->getValidatedByPerfDateFormatted(),
            'validatedByPerfMember' => $this->getValidatedByPerfMember(),
        ];
    }

    public static function getSearchFields(): array
    {
        return ['numero', 'clientName', 'contactName', 'study', 'type'];
    }

    /**
     * @return float
     */
    public function getAmountTTC()
    {
        if ($this->amountHT == null)
            return null;
        return (float)number_format($this->amountHT * (($this->taxPercentage / 100) + 1), 2);
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
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param string $numero
     */
    public function setNumero($numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return Address
     */
    public function getFullAddress()
    {
        return $this->fullAddress;
    }

    /**
     * @param Address $fullAddress
     */
    public function setFullAddress($fullAddress): void
    {
        $this->fullAddress = $fullAddress;
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
     * @return Study|null
     */
    public function getStudy()
    {
        return $this->study;
    }

    /**
     * @param Study|null $study
     */
    public function setStudy($study): void
    {
        $this->study = $study;
    }

    /**
     * @return FactureType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param FactureType $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return \DateTime
     */
    public function getAgreementSignDate()
    {
        return $this->agreementSignDate;
    }

    /**
     * @return string|null
     */
    public function getAgreementSignDateFormatted()
    {
        if ($this->getAgreementSignDate() == null)
            return null;
        return $this->getAgreementSignDate()->format('Y-m-d');
    }

    /**
     * @param \DateTime $agreementSignDate
     */
    public function setAgreementSignDate($agreementSignDate): void
    {
        $this->agreementSignDate = $agreementSignDate;
    }

    /**
     * @return float
     */
    public function getAmountHT()
    {
        if($this->amountHT == null)
            return null;
        return (float)number_format($this->amountHT, 2);
    }

    /**
     * @param float $amountHT
     */
    public function setAmountHT($amountHT): void
    {
        $this->amountHT = $amountHT;
    }

    /**
     * @return float
     */
    public function getTaxPercentage()
    {
        if ($this->taxPercentage == null)
            return null;
        return (float)number_format($this->taxPercentage, 2);
    }

    /**
     * @param float $taxPercentage
     */
    public function setTaxPercentage($taxPercentage): void
    {
        $this->taxPercentage = $taxPercentage;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return mixed
     */
    public function getDueDateFormatted()
    {
        if ($this->getDueDate() == null)
            return null;

        return $this->getDueDate()->format('Y-m-d');
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate($dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return string
     */
    public function getAdditionalInformation()
    {
        return $this->additionalInformation;
    }

    /**
     * @param string $additionalInformation
     */
    public function setAdditionalInformation($additionalInformation): void
    {
        $this->additionalInformation = $additionalInformation;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return string|null
     */
    public function getCreatedDateFormatted()
    {
        if ($this->getCreatedDate() == null)
            return null;

        return $this->getCreatedDate()->format('Y-m-d');
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
     * @return string|null
     */
    public function getValidatedByUaDateFormatted()
    {
        if ($this->getValidatedByUaDate() == null)
            return null;

        return $this->getValidatedByUaDate()->format('Y-m-d');
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
     * @return string|null
     */
    public function getValidatedByPerfDateFormatted()
    {
        if ($this->getValidatedByPerfDate() == null)
            return null;

        return $this->getValidatedByPerfDate()->format('Y-m-d');
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

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     */
    public function setContactName($contactName): void
    {
        $this->contactName = $contactName;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     */
    public function setContactEmail($contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @return FactureDocument[]
     */
    public function getRelatedDocuments(): array
    {
        $relatedDocuments = array();
        foreach ($this->relatedDocuments as $relatedDocument){
            $relatedDocuments[] = $relatedDocument;
        }
        return $relatedDocuments;
    }

    /**
     * @param FactureDocument[] $relatedDocuments
     */
    public function setRelatedDocuments(array $relatedDocuments): void
    {
        $this->relatedDocuments = $relatedDocuments;
    }
}