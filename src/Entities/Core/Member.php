<?php

namespace Keros\Entities\Core;
use JsonSerializable;
use Keros\Tools\Searchable;

/**
 * @Entity
 * @Table(name="core_member")
 */
class Member implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
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
    protected $birthDate;

    /** @Column(type="string", length=20) */
    protected $telephone;

    /** @Column(type="string", length=20) */
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
     * Member constructor.
     * @param $firstName
     * @param $lastName
     * @param $birthDate
     * @param $telephone
     * @param $email
     * @param $schoolYear
     */
    public function __construct($firstName, $lastName, $birthDate, $telephone, $email, $schoolYear)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->schoolYear = $schoolYear;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUser(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'genderId' => $this->getGender()->getId(),
            'email' => $this->getEmail(),
            'birthday' => $this->getBirthDate(),
            'departmentId' => $this->getDepartment()->getId(),
            'schoolYear' => $this->getSchoolYear(),
            'telephone' => $this->getTelephone(),
            'addressId' => $this->getAddress(),
            'positionId' => []
        ];
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
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
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
    public function getAddress()
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
}