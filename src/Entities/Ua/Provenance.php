<?php

namespace Keros\Entities\Ua;


/**
 * @Entity
 * @Table(name="ua_provenance")
 */
class Provenance
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /** @Column(type="string", length=100) */
    protected $label;


    public function __construct($label)
    {
        $this->label = $label;
    }


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
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

}