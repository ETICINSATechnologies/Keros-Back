<?php

namespace Keros\Entities\Ua;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Keros\Tools\Searchable;

/**
 * @Entity
 * @Table(name="ua_firm")
 */
class Firm implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /** @Column(type="string", length=20) */
    protected $siret;
    /** @Column(type="string", length=64) */
    protected $name;
    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Address")
     * @JoinColumn(name="addressId", referencedColumnName="id")
     **/
    protected $address;
    /**
     * @ManyToOne(targetEntity="FirmType")
     * @JoinColumn(name="typeId", referencedColumnName="id")
     **/
    protected $type;

    public function __construct($name, $siret, $address, $type)
    {
        $this->name = $name;
        $this->siret = $siret;
        $this->address = $address;
        $this->type = $type;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'siret' => $this->getSiret(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'type' => $this->getType(),
        ];
    }

    public static function getSearchFields(): array
    {
        return ['siret', 'name'];
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * @param mixed $siret
     */
    public function setSiret($siret): void
    {
        $this->siret = $siret;
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
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
}