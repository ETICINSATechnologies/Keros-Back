<?php
/**
 * Created by PhpStorm.
 * User: paulgoux
 * Date: 2019-01-15
 * Time: 22:03
 */

namespace Keros\Entities\Core;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="core_template")
 */
class Template implements JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=255) */
    protected $name;

    /** @Column(type="string", length=255) */
    protected $location;

    /** @Column(type="boolean") */
    protected $oneConsultant;
    /**
     * @ManyToOne(targetEntity="TemplateType")
     * @JoinColumn(name="typeId", referencedColumnName="id")
     **/
    protected $typeId;

    /**
     * Template constructor.
     * @param $name
     * @param $location
     * @param $typeId
     * @param $oneConsultant
     */
    public function __construct($name, $location, $typeId, $oneConsultant)
    {
        $this->name = $name;
        $this->location = $location;
        $this->typeId = $typeId;
        $this->oneConsultant = $oneConsultant;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'location' => $this->getLocation(),
            'typeId' => $this->getTypeId(),
            'oneConsultant' => $this->getOneConsultant(),
        ];
    }

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
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param mixed $typeId
     */
    public function setTypeId($typeId): void
    {
        $this->typeId = $typeId;
    }

    /**
     * @return mixed
     */
    public function getOneConsultant()
    {
        return $this->oneConsultant;
    }

    /**
     * @param mixed $oneConsultant
     */
    public function setOneConsultant($oneConsultant): void
    {
        $this->oneConsultant = $oneConsultant;
    }


}