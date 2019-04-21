<?php

namespace Keros\Entities\Treso;

/**
 * @Entity
 * @Table(name="treso_facture_document_type")
 */
class FactureDocumentType implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=255) */
    protected $location;

    /** @Column(type="boolean") */
    protected $isTemplatable;

    /**
     * @OneToOne(targetEntity="Keros\Entities\Treso\FactureType")
     * @JoinColumn(name="factureTypeId", referencedColumnName="id")
     **/
    protected $factureType;

    /**
     * StudyDocumentType constructor.
     * @param $location
     * @param $isTemplatable
     */
    public function __construct($location, $isTemplatable)
    {
        $this->location = $location;
        $this->isTemplatable = $isTemplatable;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'location' => $this->getLocation(),
            'isTemplatable' => $this->getIsTemplatable(),
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
    public function getisTemplatable()
    {
        return $this->isTemplatable;
    }

    /**
     * @param mixed $isTemplatable
     */
    public function setIsTemplatable($isTemplatable): void
    {
        $this->isTemplatable = $isTemplatable;
    }

    /**
     * @return FactureType|null
     */
    public function getFactureType() : ?FactureType
    {
        return $this->factureType;
    }

    /**
     * @param mixed $factureType
     */
    public function setFactureType($factureType): void
    {
        $this->factureType = $factureType;
    }

}