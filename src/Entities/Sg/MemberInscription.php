<?php

namespace Keros\Entities\Sg;

use JsonSerializable;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\Country;
use Keros\Entities\Core\Department;
use Keros\Entities\Core\Pole;

/**
 * @Entity
 * @Table(name="sg_member_inscription")
 */
class MemberInscription implements JsonSerializable
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
     * @var Pole
     * @Column(type="integer")
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="nationalityId", referencedColumnName="id")
     */
    private $wantedPole;

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
     * @param $wantedPole
     */
    public function __construct($firstName, $lastName, $department, $email, $phoneNumber, $outYear, $nationality, $address, $wantedPole)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->department = $department;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->outYear = $outYear;
        $this->nationality = $nationality;
        $this->address = $address;
        $this->wantedPole = $wantedPole;
    }

    /**
     * @return array|mixed
     */
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

    public static function getSearchFields(): array {
        return ['firstName', 'lastName', 'email', 'departement', 'phoneNumber', 'outYear', 'wantedPole'];
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
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return int
     */
    public function getOutYear(): int
    {
        return $this->outYear;
    }

    /**
     * @param int $outYear
     */
    public function setOutYear(int $outYear): void
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
     * @return Pole
     */
    public function getWantedPole(): Pole
    {
        return $this->wantedPole;
    }

    /**
     * @param Pole $wantedPole
     */
    public function setWantedPole(Pole $wantedPole): void
    {
        $this->wantedPole = $wantedPole;
    }


}