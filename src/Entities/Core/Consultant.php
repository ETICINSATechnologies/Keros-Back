<?php

namespace Keros\Entities\Core;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="core_consultant")
 */
class Consultant implements JsonSerializable
{
    /**
     * @Id
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @ManyToOne(targetEntity="Gender")
     * @JoinColumn(name="GenderId", referencedColumnName="id")
     **/
    protected $gender;

    /** @Column(type="string", length=100) */
    protected $firstName;

    /** @Column(type="string", length=100) */
    protected $lastName;

    /** @Column(type="datetime") */
    protected $birthday;

    /** 
     * @var string|null
     * @Column(type="string", length=20)
    */
    protected $telephone;

    /** @Column(type="string", length=255) */
    protected $email;

    /** @Column(type="string", Length=255 */
    private $nationality;

    /**
     * @ManyToOne(targetEntity="Address")
     * @JoinColumn(name="AddressId", referencedColumnName="id")
     **/
    protected $address;

    /** @Column(type="integer") */
    protected $schoolYear;

    /**
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="DepartmentId", referencedColumnName="id")
     **/
    protected $department;

    /** @Column(type="string", length=20) */
    protected $company;

    /** @Column(type="string", length=200) */
    protected $profilePicture;

    /**
     * @var string|null
     * @Column(type="string", length=255)
     */
    protected $socialSecurityNumber;

    /**
     * @Column(type="boolean")
     */
    protected $droitImage;

    /**
     * @Column(type="boolean")
     */
    protected $isApprentice;

    /**
     * @Column(type="datetime")
     */
    protected $createdDate;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Ua\Study", mappedBy="consultants")
     */
    protected $studiesAsConsultant;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentIdentity;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentScolaryCertificate;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentRIB;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentVitaleCard;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentResidencePermit;

    /** 
     * @var string|null
     * @Column(type="string", length=200)
     */
    private $documentCVEC;

    public function __construct($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $nationality, $gender, $department, $company, $profilePicture, $socialSecurityNumber, $droitImage, $isApprentice, $createdDate, $documentIdentity, $documentScolaryCertificate, $documentRIB, $documentVitaleCard, $documentResidencePermit, $documentCVEC)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->schoolYear = $schoolYear;
        $this->nationality = $nationality;
        $this->gender = $gender;
        $this->department = $department;
        $this->company = $company;
        $this->profilePicture = $profilePicture;
        $this->socialSecurityNumber = $socialSecurityNumber;
        $this->droitImage = $droitImage;
        $this->isApprentice = $isApprentice;
        $this->createdDate = $createdDate;
        $this->documentIdentity = $documentIdentity;
        $this->documentScolaryCertificate = $documentScolaryCertificate;
        $this->documentRIB = $documentRIB;
        $this->documentVitaleCard = $documentVitaleCard;
        $this->documentResidencePermit = $documentResidencePermit;
        $this->$documentCVEC = $documentCVEC;
    }
    public function jsonSerialize()
    {
        return [
            'id' => $this->getUser()->getId(),
            'username' => $this->getUser()->getUsername(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'gender' => $this->getGender(),
            'email' => $this->getEmail(),
            'birthday' => $this->getBirthday()->format('Y-m-d'),
            'department' => $this->getDepartment(),
            'schoolYear' => $this->getSchoolYear(),
            'nationality' => $this->getNationality(),
            'telephone' => $this->getTelephone(),
            'address' => $this->getAddress(),
            'company' => $this->getCompany(),
            'profilePicture' => $this->getProfilePicture(),
            'droitImage' => $this->isDroitImage(),
            'isApprentice' => $this->getIsApprentice(),
            'createdDate' => $this->getCreatedDate(),
        ];
    }
    public function getProtected()
    {
        return [
            'id' => $this->getUser()->getId(),
            'username' => $this->getUser()->getUsername(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'gender' => $this->getGender(),
            'email' => $this->getEmail(),
            'birthday' => $this->getBirthday()->format('Y-m-d'),
            'department' => $this->getDepartment(),
            'schoolYear' => $this->getSchoolYear(),
            'nationality' => $this->getNationality(),
            'telephone' => $this->getTelephone(),
            'address' => $this->getAddress(),
            'company' => $this->getCompany(),
            'profilePicture' => $this->getProfilePicture(),
            'droitImage' => $this->isDroitImage(),
            'isApprentice' => $this->getIsApprentice(),
            'createdDate' => $this->getCreatedDate(),
            'socialSecurityNumber' => $this->getSocialSecurityNumber(),
        ];
    }
    public static function getSearchFields(): array {
        return ['firstName', 'lastName', 'company'];
    }
    // Getters and setters
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getUser()->getId();
    }
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
    /**
     * @return mixed
     */
    public function getGender() : Gender
    {
        return $this->gender;
    }
    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }
    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }
    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }
    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday): void
    {
        $this->birthday = $birthday;
    }
    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
    /**
     * @return mixed
     */
    public function getNationality()
    {
        return $this->$nationality;
    }
    /**
     * @param mixed $nationality
     */
    public function setNationality($nationality): void
    {
        $this->$nationality = $nationality;
    }
    /**
     * @return mixed
     */
    public function getAddress() : Address
    {
        return $this->address;
    }
    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }
    /**
     * @return string
     */
    public function getSocialSecurityNumber(): ?string
    {
        return $this->socialSecurityNumber;
    }
    /**
     * @param string|null $socialSecurityNumber
     */
    public function setSocialSecurityNumber(?string $socialSecurityNumber): void
    {
        $this->socialSecurityNumber = $socialSecurityNumber;
    }
    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }
    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }
    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }
    /**
     * @param mixed $profilePicture
     */
    public function setProfilePicture($profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
    /**
     * @return mixed
     */
    public function getSchoolYear()
    {
        return $this->schoolYear;
    }
    /**
     * @param mixed $schoolYear
     */
    public function setSchoolYear($schoolYear): void
    {
        $this->schoolYear = $schoolYear;
    }
    /**
     * @return Department
     */
    public function getDepartment() : Department
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
     * @return mixed
     */
    public function getStudiesAsConsultant()
    {
        $studies = [];
        foreach ($this->studiesAsConsultant as $study)
        {
            $studies[] = $study;
        }
        return $studies;
    }
    /**
     * @param mixed $studiesAsConsultant
     */
    public function setStudiesAsConsultant($studiesAsConsultant): void
    {
        $this->studiesAsConsultant = $studiesAsConsultant;
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
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }
    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate(\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
    /**
     * @return string
     */
    public function getDocumentIdentity(): ?string
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
    public function getDocumentScolaryCertificate(): ?string
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
    public function getDocumentRIB(): ?string
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
    public function getDocumentVitaleCard(): ?string
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
     * @return string|null
     */
    public function getDocumentCVEC(): ?string
    {
        return $this->documentCVEC;
    }

    /**
     * @param string|null $documentCVEC
     */
    public function setDocumentCVEC(?string $documentCVEC): void
    {
        $this->documentCVEC = $documentCVEC;
    }

}