<?php

namespace Keros\Entities\Treso;

/**
 * @Entity
 * @Table(name="treso_facture_type")
 */
class FactureType implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=255) */
    protected $label;

    /**
     * FactureType constructor.
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
            'label' => $this->getLabel()
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }


}