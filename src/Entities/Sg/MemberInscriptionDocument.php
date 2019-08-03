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
     * @ManyToOne(targetEntity="Keros\Entities\Sg\MemberInscription")
     * @JoinColumn(name="memberInscriptionId", referencedColumnName="id")
     */
    private $memberInscription;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Sg\MemberInscriptionDocumentType")
     * @JoinColumn(name="memberInscriptionDocumentTypeId", referencedColumnName="id")
     */
    private $memberInscriptionDocumentType;

    public function __construct($date, $location, $memberInscription, $memberInscriptionDocumentType)
    {
        parent::__construct($date, $location);
        $this->memberInscription = $memberInscription;
        $this->memberInscriptionDocumentType = $memberInscriptionDocumentType;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'memberInscription' => $this->getMemberInscription(),
            'memberInscriptionDocumentType' => $this->getMemberInscriptionDocumentType(),
            'date' => $this->getUploadDate(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return mixed
     */
    public function getMemberInscription()
    {
        return $this->memberInscription;
    }

    /**
     * @param mixed $memberInscription
     */
    public function setMemberInscription($memberInscription): void
    {
        $this->memberInscription = $memberInscription;
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