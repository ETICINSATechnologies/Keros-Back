<?php

namespace Keros\Entities\Sg;

use JsonSerializable;
use Keros\Entities\Core\Document;

/**
 * Class MemberInscriptionDocument
 * @package Keros\Entities\Sg
 * @Entity
 * @Table(name="sg_member_inscription_document")
 */
class MemberInscriptionDocument extends Document implements JsonSerializable
{

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Sg\MemberInscriptionDocumentType")
     * @JoinColumn(name="memberInscriptionDocumentTypeId", referencedColumnName="id")
     */
    private $memberInscriptionDocumentType;

    public function __construct($date, $location, $memberInscription, $memberInscriptionDocumentType)
    {
        parent::__construct($date, $location);
        $this->memberInscriptionDocumentType = $memberInscriptionDocumentType;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'memberInscriptionDocumentType' => $this->getMemberInscriptionDocumentType(),
            'date' => $this->getUploadDate(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return mixed
     */
    public function getMemberInscriptionDocumentType()
    {
        return $this->memberInscriptionDocumentType;
    }

    /**
     * @param mixed $memberInscriptionDocumentType
     */
    public function setMemberInscriptionDocumentType($memberInscriptionDocumentType): void
    {
        $this->memberInscriptionDocumentType = $memberInscriptionDocumentType;
    }
}