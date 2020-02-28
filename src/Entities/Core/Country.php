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
     * @Column(type="boolean", nullable=false)
     * @var boolean
     */
    protected $isEu;

    /**
     * Country constructor.
     * @param $label
     * @param $isEu
     */
    public function __construct($label, $isEu)
    {
        $this->label = $label;
        $this->isEu = $isEu;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'isEu' => $this->getIsEu()
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

    /**
     * @return mixed
     */
    public function getIsEu()
    {
        return $this->isEu;
    }
}
