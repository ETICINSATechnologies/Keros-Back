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
 * @Table(name="core_country")
 */
class Country implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=64) */
    protected $label;

    /**
     * Country constructor.
     * @param $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
        ];
    }

    public static function getSearchFields(): array {
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }
}