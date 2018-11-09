<?php

namespace Keros\Entities\Ua;
use JsonSerializable;


/**
 * @Entity
 * @Table(name="ua_contact")
 */
class Contact implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=100) */
    protected $firstName;

    /** @Column(type="string", length=100) */
    protected $lastName;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Gender")
     * @JoinColumn(name="GenderId", referencedColumnName="id")
     **/
    protected $gender;

    /**
     * @ManyToOne(targetEntity="Firm")
     * @JoinColumn(name="FirmId", referencedColumnName="id")
     **/
    protected $firm;

    /** @Column(type="string", length=255) */
    protected $email;

    /** @Column(type="string", length=20) */
    protected $telephone;

    /** @Column(type="string", length=20) */
    protected $cellphone;

    /** @Column(type="string", length=255) */
    protected $position;

    /** @Column(type="string", length=255) */
    protected $notes;

    /** @Column(type="boolean", length=50) */
    protected $old;

    /**
     * Contact constructor.
     * @param $firstName
     * @param $lastName
     * @param $gender
     * @param $firm
     * @param $email
     * @param $old
     */
    public function __construct($firstName, $lastName, $gender, $firm, $email, $old)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->firm = $firm;
        $this->email = $email;
        $this->old = $old;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'gender' => $this->getGender(),
            'firm' => $this->getFirm(),
            'email' => $this->getEmail(),
            'telephone' => $this->getTelephone(),
            'cellphone' => $this->getCellphone(),
            'position' => $this->getPosition(),
            'notes' => $this->getNotes(),
            'old' => $this->getOld()
        ];
    }

    public static function getSearchFields(): array {
        return ['firstName', 'lastName', 'email'];
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
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * @param mixed $cellphone
     */
    public function setCellphone($cellphone): void
    {
        $this->cellphone = $cellphone;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes): void
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * @param mixed $old
     */
    public function setOld($old): void
    {
        $this->old = $old;
    }
}