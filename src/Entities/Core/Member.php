<?php

namespace Keros\Entities\Core;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="core_member")
 */
class Member implements JsonSerializable
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

    /** @Column(type="string", length=20) */
    protected $telephone;

    /** @Column(type="string", length=255) */
    protected $email;

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

    /**
     * @OneToMany(targetEntity="MemberPosition", mappedBy="member")
     */
    protected $memberPositions;

    /** @Column(type="string", length=20) */
    protected $company;

    /** @Column(type="string", length=200) */
    protected $profilePicture;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Ua\Study", mappedBy="qualityManagers")
     */
    protected $studiesAsQualityManager;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Ua\Study", mappedBy="consultants")
     */
    protected $studiesAsConsultant;

    /**
     * @ManyToMany(targetEntity="Keros\Entities\Ua\Study", mappedBy="leaders")
     */
    protected $studiesAsLeader;
    
    public function __construct($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $gender, $department, $company, $profilePicture)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->schoolYear = $schoolYear;
        $this->gender = $gender;
        $this->department = $department;
        $this->company = $company;
        $this->profilePicture = $profilePicture;
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
            'telephone' => $this->getTelephone(),
            'address' => $this->getAddress(),
            'positions' => $this->getPositionsArray(),
            'company' => $this->getCompany(),
            'profilePicture' => $this->getProfilePicture(),
        ];
    }

    public static function getSearchFields(): array {
        return ['username', 'firstName', 'lastName', 'email'];
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
     * @return mixed
     */
    public function getUser()
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
    public function getGender()
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
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    public function getMemberPositions()
    {
        return $this->memberPositions;
    }

    /**
     * @param mixed $memberPositions
     */
    public function setMemberPositions($memberPositions): void
    {
        $this->memberPositions = $memberPositions;
    }

    /**
     * @return array
     */
    public function getPositionsArray()
    {
        $positions = [];
        foreach ($this->getMemberPositions() as $position)
        {
            $positions[] = $position;
        }

        return $positions;
    }


    /**
     * @return mixed
     */
    public function getStudiesAsQualityManager()
    {
        $studies = [];
        foreach ($this->studiesAsQualityManager as $study)
        {
            $studies[] = $study;
        }

        return $studies;
    }

    /**
     * @param mixed $studiesAsQualityManager
     */
    public function setStudiesAsQualityManager($studiesAsQualityManager): void
    {
        $this->studiesAsQualityManager = $studiesAsQualityManager;
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
     * @return mixed
     */
    public function getStudiesAsLeader()
    {
        $studies = [];
        foreach ($this->studiesAsLeader as $study)
        {
            $studies[] = $study;
        }

        return $studies;
    }

    /**
     * @param mixed $studiesAsLeader
     */
    public function setStudiesAsLeader($studiesAsLeader): void
    {
        $this->studiesAsLeader = $studiesAsLeader;
    }

}