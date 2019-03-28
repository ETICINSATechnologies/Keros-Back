<?php

namespace Keros\Entities\Sg;

/**
 * @Entity
 * @Table(name="sg_member_inscription")
 */
class MemberInscription implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /** @Column(type="string", length=255) */
    private $firstName;

    /** @Column(type="string", length=255) */
    private $lastName;

    /**
     * @Column(type="integer")
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="departmentId", referencedColumnName="id")
     */
    private $department;

    /** @Column(type="string", length=255) */
    private $email;

    /** @Column(type="string", length=255) */
    private $phoneNumber;

    /** @Column(type="integer") */
    private $outYear;

    /**
     * @Column(type="integer")
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="nationalityId", referencedColumnName="id")
     */
    private $nationality;

    /**
     * @Column(type="integer")
     * @ManyToOne(targetEntity="Address")
     * @JoinColumn(name="addressId", referencedColumnName="id")
     */
    private $address;

    /**
     * MemberInscription constructor.
     * @param $firstName
     * @param $lastName
     * @param $department
     * @param $email
     * @param $phoneNumber
     * @param $outYear
     * @param $nationality
     * @param $address
     */
    public function __construct($firstName, $lastName, $department, $email, $phoneNumber, $outYear, $nationality, $address)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->department = $department;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->outYear = $outYear;
        $this->nationality = $nationality;
        $this->address = $address;
    }

    public function jsonSerialize()
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'department' => $this->getDepartment(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'outYear' => $this->getOutYear(),
            'nationality' => $this->getNationality(),
            'address' => $this->getAddress(),
        ];
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getOutYear()
    {
        return $this->outYear;
    }

    /**
     * @param mixed $outYear
     */
    public function setOutYear($outYear): void
    {
        $this->outYear = $outYear;
    }

    /**
     * @return mixed
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param mixed $nationality
     */
    public function setNationality($nationality): void
    {
        $this->nationality = $nationality;
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


}