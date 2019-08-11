<?php

namespace Keros\Entities\Sg;

use JsonSerializable;
use Keros\Entities\Core\Document;
use Keros\Entities\Core\Member;

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

    /**
     * @var MemberInscription|null
     * @ManyToOne(targetEntity="MemberInscription", inversedBy="memberInscriptionDocuments")
     * @JoinColumn(name="memberInscriptionId", referencedColumnName="id")
     */
    private $memberInscription;

    /**
     * @var Member|null
     * @ManyToOne(targetEntity="Keros\Entities\Core\Member", inversedBy="memberInscriptionDocuments")
     * @JoinColumn(name="memberId", referencedColumnName="id")
     */
    private $member;

    /**
     * MemberInscriptionDocument constructor.
     * @param $date
     * @param $location
     * @param $memberInscription
     * @param $memberInscriptionDocumentType
     * @param $member
     */
    public function __construct($date, $location, $memberInscription, $memberInscriptionDocumentType, $member)
    {
        parent::__construct($date, $location);
        $this->memberInscription = $memberInscription;
        $this->memberInscriptionDocumentType = $memberInscriptionDocumentType;
        $this->member = $member;
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

    /**
     * @return MemberInscription|null
     */
    public function getMemberInscription(): ?MemberInscription
    {
        return $this->memberInscription;
    }

    /**
     * @param MemberInscription|null $memberInscription
     */
    public function setMemberInscription(?MemberInscription $memberInscription): void
    {
        $this->memberInscription = $memberInscription;
    }

    /**
     * @return Member|null
     */
    public function getMember(): ?Member
    {
        return $this->member;
    }

    /**
     * @param Member|null $member
     */
    public function setMember(?Member $member): void
    {
        $this->member = $member;
    }
}