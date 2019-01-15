<?php
/**
 * Created by PhpStorm.
 * User: paulgoux
 * Date: 2019-01-15
 * Time: 22:03
 */

namespace Keros\Entities\Core;

use JsonSerializable;

class Template implements JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=64) */
    protected $name;

    /** @Column(type="string", length=64) */
    protected $location;

    /**
     * @ManyToOne(targetEntity="TemplateType")
     * @JoinColumn(name="typeId", referencedColumnName="id")
     **/
    protected $type;

    /**
     * Template constructor.
     * @param $id
     * @param $nom
     * @param $location
     * @param $typeId
     */
    public function __construct($id, $nom, $location, $typeId)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->location = $location;
        $this->type = $typeId;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'location' => $this->getLocation(),
            'type' => $this->getType(),
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