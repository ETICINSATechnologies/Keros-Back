<?php

namespace Keros\Entities\Sg;

use JsonSerializable;
use Keros\Entities\Core\Document;

/**
 * Class MemberInscrptionDocument
 * @package Keros\Entities
 * @Entity
 * @Table(name="sg_member_inscrption_document")
 */
class MemberInscriptionDocument extends Document implements JsonSerializable
{

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Sg\MemberInscription")s
     * @JoinColumn(name="memberInscrptionId", referencedColumnName="id")
     **/
    protected $memberInscription;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Sg\MemberInscriptionDocumentType")
     * @JoinColumn(name="memberInscrptionDocumentTypeId", referencedColumnName="id")
     **/
    protected $memberInscriptionDocumentType;

    /**
     * MemberInscriptionDocument constructor.
     * @param $date
     * @param $location
     * @param $memberInscription
     * @param $memberInscriptionDocumentType
     */
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
            'memberInscrption' => $this->getMemberInscription(),
            'memberInscrptionDocumentType' => $this->getMemberInscriptionDocumentType(),
            'date' => $this->getUploadDate(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return MemberInscription
     */
    public function getMemberInscription() : MemberInscription
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
