<?php

namespace Keros\Entities\Core;

use JsonSerializable;
use Keros\Tools\Searchable;

/**
 * @Entity
 * @Table(name="core_address")
 */
class Address implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=64) */
    protected $line1;

    /** @Column(type="string", length=64) */
    protected $line2;

    /** @Column(type="integer", length=10) */
    protected $postalCode;

    /** @Column(type="string", length=64) */
    protected $city;

    /**
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="countryId", referencedColumnName="id")
     **/
    protected $country;


    /**
     *  constructor.
     * @param $line1
     * @param $line2
     * @param $postalCode
     * @param $city
     */
    public function __construct($line1, $line2, $postalCode, $city, $country)
    {
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'line1' => $this->getLine1(),
            'line2' => $this->getLine2(),
            'postalCode' => $this->getPostalCode(),
            'city' => $this->getCity(),
            'country' => $this->getCountry()
        ];
    }

    public static function getSearchFields(): array
    {
        return ['label'];
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
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * @param $line1
     */
    public function setLine1($line1): void
    {
        $this->line1 = $line1;
    }

    /**
     * @return mixed
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * @param mixed $line2
     */
    public function setLine2($line2): void
    {
        $this->line2 = $line2;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }
}