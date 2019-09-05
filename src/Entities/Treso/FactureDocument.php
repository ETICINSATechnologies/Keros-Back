<?php

namespace Keros\Entities\Treso;

use JsonSerializable;
use Keros\Entities\Core\Document;

/**
 * Class FactureDocument
 * @package Keros\Entities
 * @Entity
 * @Table(name="treso_facture_document")
 */
class FactureDocument extends Document implements JsonSerializable
{

    /**
     * @var Facture|null
     * @ManyToOne(targetEntity="Keros\Entities\Treso\Facture")
     * @JoinColumn(name="factureId", referencedColumnName="id")
     **/
    protected $facture;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Treso\FactureDocumentType")
     * @JoinColumn(name="factureDocumentTypeId", referencedColumnName="id")
     **/
    protected $factureDocumentType;

    /**
     * FactureDocument constructor.
     * @param $date
     * @param $location
     * @param $facture
     * @param $factureDocumentType
     */
    public function __construct($date, $location, $facture, $factureDocumentType)
    {
        parent::__construct($date, $location);
        $this->facture = $facture;
        $this->factureDocumentType = $factureDocumentType;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'facture' => $this->getFacture(),
            'factureDocumentType' => $this->getFactureDocumentType(),
            'date' => $this->getUploadDate(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return Facture|null
     */
    public function getFacture() : ?Facture
    {
        return $this->facture;
    }

    /**
     * @param mixed $facture
     */
    public function setFacture($facture): void
    {
        $this->facture = $facture;
    }

    /**
     * @return mixed
     */
    public function getFactureDocumentType()
    {
        return $this->factureDocumentType;
    }

    /**
     * @param mixed $factureDocumentType
     */
    public function setFactureDocumentType($factureDocumentType): void
    {
        $this->factureDocumentType = $factureDocumentType;
    }

}
