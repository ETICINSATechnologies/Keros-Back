<?php

namespace Keros\Entities\Sg;

use JsonSerializable;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\Country;
use Keros\Entities\Core\Department;
use Keros\Entities\Core\Gender;
use DateTime;

/**
 * @Entity
 * @Table(name="sg_consultant_inscription")
 */
class ConsultantInscription implements JsonSerializable
{
    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @var Gender
     * @ManyToOne(targetEntity="Keros\Entities\Core\Gender")
     * @JoinColumn(name="genderId", referencedColumnName="id")
     **/
    private $gender;

    /**
     * @var DateTime
     * @Column(type="date")
     */
    private $birthday;

    /**
     * @var Department
     * @ManyToOne(targetEntity="Keros\Entities\Core\Department")
     * @JoinColumn(name="departmentId", referencedColumnName="id")
     */
    private $department;

    /**
     * @var string
     * @Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string|null
     * @Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @var int|null
     * @Column(type="integer")
     */
    private $outYear;

    /**
     * @var Country
     * @ManyToOne(targetEntity="Keros\Entities\Core\Country")
     * @JoinColumn(name="nationalityId", referencedColumnName="id")
     */
    private $nationality;

    /**
     * @var Address
     * @ManyToOne(targetEntity="Keros\Entities\Core\Address")
     * @JoinColumn(name="addressId", referencedColumnName="id")
     */
    private $address;

    /**
     * @var string
     * @Column(type="string", length=255)
     */
    private $socialSecurityNumber;

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    private $droitImage;

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    private $isApprentice;

    /**
     * @var DateTime
     * @Column(type="datetime")
     */
    private $createdDate;

    /** 
     * @var string
     * @Column(type="string", length=200)
     */
    private $documentIdentity;

    /** 
     * @var string
     * @Column(type="string", length=200)
     */
    private $documentScolaryCertificate;

    /** 
     * @var string
     * @Column(type="string", length=200)
     */
    private $documentRIB;

    /** 
     * @var string
     * @Column(type="string", length=200)
     */
    private $documentVitaleCard;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentResidencePermit;

    /** 
     * @var string
     * @Column(type="string", length=200)
     */
    private $documentCVEC;

    /**
     * ConsultantInscription constructor.
     * @param string $firstName
     * @param string $lastName
     * @param Gender $gender
     * @param DateTime $birthday
     * @param Department $department
     * @param string $email
     * @param string|null $phoneNumber
     * @param int|null $outYear
     * @param Country $nationality
     * @param Address $address
     * @param string $socialSecurityNumber
     * @param bool $droitImage
     * @param bool $isApprentice
     * @param DateTime $createdDate
     * @param string $documentIdentity
     * @param string $documentScolaryCertificate
     * @param string $documentRIB
     * @param string $documentVitaleCard
     * @param string $documentResidencePermit
     * @param string $documentCVEC
     */
    public function __construct(string $firstName, string $lastName, Gender $gender, DateTime $birthday, Department $department, string $email, ?string $phoneNumber, ?int $outYear, Country $nationality, Address $address, string $socialSecurityNumber, bool $droitImage, bool $isApprentice, DateTime $createdDate, string $documentIdentity, string $documentScolaryCertificate, string $documentRIB, string $documentVitaleCard, ?string $documentResidencePermit, string $documentCVEC)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->birthday = $birthday;
        $this->department = $department;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->outYear = $outYear;
        $this->nationality = $nationality;
        $this->address = $address;
        $this->socialSecurityNumber = $socialSecurityNumber;
        $this->droitImage = $droitImage;
        $this->isApprentice = $isApprentice;
        $this->createdDate = $createdDate;
        $this->documentIdentity = $documentIdentity;
        $this->documentScolaryCertificate = $documentScolaryCertificate;
        $this->documentRIB = $documentRIB;
        $this->documentVitaleCard = $documentVitaleCard;
        $this->documentResidencePermit = $documentResidencePermit;
        $this->documentCVEC = $documentCVEC;
    }


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'gender' => $this->getGender(),
            'birthday' => $this->getBirthday()->format('Y-m-d'),
            'department' => $this->getDepartment(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'outYear' => $this->getOutYear(),
            'nationality' => $this->getNationality(),
            'address' => $this->getAddress(),
            'socialSecurityNumber' => $this->getSocialSecurityNumber(),
            'droitImage' => $this->isDroitImage(),
            'isApprentice' => $this->getIsApprentice(),
            'createdDate' => $this->getCreatedDate(),
        ];
    }

    public static function getSearchFields(): array
    {
        return ['firstName', 'lastName', 'email', 'phoneNumber', 'outYear'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Gender
     */
    public function getGender(): Gender
    {
        return $this->gender;
    }

    /**
     * @param Gender $gender
     */
    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     */
    public function setBirthday(DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return Department
     */
    public function getDepartment(): Department
    {
        return $this->department;
    }

    /**
     * @param Department $department
     */
    public function setDepartment(Department $department): void
    {
        $this->department = $department;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return int|null
     */
    public function getOutYear(): ?int
    {
        return $this->outYear;
    }

    /**
     * @param int|null $outYear
     */
    public function setOutYear(?int $outYear): void
    {
        $this->outYear = $outYear;
    }

    /**
     * @return Country
     */
    public function getNationality(): Country
    {
        return $this->nationality;
    }

    /**
     * @param Country $nationality
     */
    public function setNationality(Country $nationality): void
    {
        $this->nationality = $nationality;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getSocialSecurityNumber(): string
    {
        return $this->socialSecurityNumber;
    }

    /**
     * @param string $socialSecurityNumber
     */
    public function setSocialSecurityNumber(string $socialSecurityNumber): void
    {
        $this->socialSecurityNumber = $socialSecurityNumber;
    }


    /**
     * @return bool
     */
    public function isDroitImage(): bool
    {
        return $this->droitImage;
    }

    /**
     * @param bool $droitImage
     */
    public function setDroitImage(bool $droitImage): void
    {
        $this->droitImage = $droitImage;
    }

    /**
     * @return bool
     */
    public function getIsApprentice(): bool
    {
        return $this->isApprentice;
    }

    /**
     * @param bool $isApprentice
     */
    public function setIsApprentice(bool $isApprentice): void
    {
        $this->isApprentice = $isApprentice;
    }

    /**
     * @return DateTime
     */
    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param DateTime $createdDate
     */
    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return string
     */
    public function getDocumentIdentity(): string
    {
        return $this->documentIdentity;
    }

    /**
     * @param string $documentIdentity
     */
    public function setDocumentIdentity(string $documentIdentity): void
    {
        $this->documentIdentity = $documentIdentity;
    }

    /**
     * @return string
     */
    public function getDocumentScolaryCertificate(): string
    {
        return $this->documentScolaryCertificate;
    }

    /**
     * @param string $documentScolaryCertificate
     */
    public function setDocumentScolaryCertificate(string $documentScolaryCertificate): void
    {
        $this->documentScolaryCertificate = $documentScolaryCertificate;
    }

    /**
     * @return string
     */
    public function getDocumentRIB(): string
    {
        return $this->documentRIB;
    }

    /**
     * @param string $documentRIB
     */
    public function setDocumentRIB(string $documentRIB): void
    {
        $this->documentRIB = $documentRIB;
    }

    /**
     * @return string
     */
    public function getDocumentVitaleCard(): string
    {
        return $this->documentVitaleCard;
    }

    /**
     * @param string $documentVitaleCard
     */
    public function setDocumentVitaleCard(string $documentVitaleCard): void
    {
        $this->documentVitaleCard = $documentVitaleCard;
    }

    /**
     * @return string|null
     */
    public function getDocumentResidencePermit(): ?string
    {
        return $this->documentResidencePermit;
    }

    /**
     * @param string|null $documentResidencePermit
     */
    public function setDocumentResidencePermit(?string $documentResidencePermit): void
    {
        $this->documentResidencePermit = $documentResidencePermit;
    }

    /**
     * @return string
     */
    public function getDocumentCVEC(): string
    {
        return $this->documentCVEC;
    }

    /**
     * @param string $documentCVEC
     */
    public function setDocumentCVEC(string $documentCVEC): void
    {
        $this->documentCVEC = $documentCVEC;
    }
}
