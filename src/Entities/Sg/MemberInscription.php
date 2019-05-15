<?php

namespace Keros\Entities\Sg;

use JsonSerializable;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\Country;
use Keros\Entities\Core\Department;
use Keros\Entities\Core\Gender;
use Keros\Entities\Core\Pole;
use DateTime;

/**
 * @Entity
 * @Table(name="sg_member_inscription")
 */
class MemberInscription implements JsonSerializable
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
     * @var string
     * @Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @var int
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
     * @var Pole
     * @ManyToOne(targetEntity="Keros\Entities\Core\Pole")
     * @JoinColumn(name="wantedPoleId", referencedColumnName="id")
     */
    private $wantedPole;

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    private $hasPaid;

    /**
     * MemberInscription constructor.
     * @param string $firstName
     * @param string $lastName
     * @param Gender $gender
     * @param DateTime $birthday
     * @param Department $department
     * @param string $email
     * @param string $phoneNumber
     * @param int $outYear
     * @param Country $nationality
     * @param Address $address
     * @param Pole $wantedPole
     * @param bool $hasPaid
     */
    public function __construct(string $firstName, string $lastName, Gender $gender, DateTime $birthday, Department $department, string $email, string $phoneNumber, int $outYear, Country $nationality, Address $address, Pole $wantedPole, bool $hasPaid)
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
        $this->wantedPole = $wantedPole;
        $this->hasPaid = $hasPaid;
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
            'wantedPole' => $this->getWantedPole(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'outYear' => $this->getOutYear(),
            'nationality' => $this->getNationality(),
            'address' => $this->getAddress(),
            'hasPaid' => $this->isHasPaid(),
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

    /**
     * @return bool
     */
    public function isHasPaid(): bool
    {
        return $this->hasPaid;
    }

    /**
     * @param bool $hasPaid
     */
    public function setHasPaid(bool $hasPaid): void
    {
        $this->hasPaid = $hasPaid;
    }

}