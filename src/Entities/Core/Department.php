<?php


namespace Keros\Entities\Core;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Keros\Tools\Searchable;

/**
 * @Entity
 * @Table(name="core_department")
 */
class Department implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /** @Column(type="string", length=15) */
    protected $label;
    /** @Column(type="string", length=64) */
    protected $name;


    /**
     * Department constructor.
     * @param $id
     * @param $label
     * @param $name
     *
     */
    public function __construct($id, $label,$name)
    {
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'name' => $this->getName()
        ];
    }

    public static function getSearchFields(): array {
        return ['name', 'label'];
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

}