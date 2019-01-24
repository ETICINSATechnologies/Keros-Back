<?php

namespace Keros\Entities\Core;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="core_member_position")
 */
class MemberPosition implements JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @ManyToOne(targetEntity="Member", inversedBy="memberPositions")
     * @JoinColumn(name="memberId", referencedColumnName="id")
     **/
    protected $member;

    /**
     * @ManyToOne(targetEntity="Position")
     * @JoinColumn(name="positionId", referencedColumnName="id")
     **/
    protected $positionId;

    /** @Column(type="boolean") */
    protected $isBoard;

    /** @Column(type="string", length=20) */
    protected $year;
    
    public function __construct($positionId, $isBoard, $year)
    {

        $this->positionId = $positionId;
        $this->isBoard = $isBoard;
        $this->year = $year;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getPositionId()->getId(),
            'label' => $this->getPositionId()->getLabel(),
            'isBoard' => $this->getIsBoard(),
            'year' => $this->getYear(),
            'pole' => $this->getPositionId()->getPole(),
        ];
    }

    public static function getSearchFields(): array {
        return ['memberId', 'positionId', 'isBoard', 'year'];
    }

    // Getters and setters
    /**
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @return mixed
     */
    public function getPositionId()
    {
        return $this->positionId;
    }

    /**
     * @return mixed
     */
    public function getIsBoard()
    {
        return $this->isBoard;
    }

    /**
     * @param mixed $isBoard
     */
    public function setIsBoard($isBoard): void
    {
        $this->isBoard = $isBoard;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

}