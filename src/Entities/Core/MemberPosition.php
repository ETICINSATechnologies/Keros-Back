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
     * @ManyToOne(targetEntity="Member")
     * @JoinColumn(name="MemberId", referencedColumnName="id")
     **/
    protected $memberId;

    /**
     * @ManyToOne(targetEntity="Position")
     * @JoinColumn(name="PositionId", referencedColumnName="id")
     **/
    protected $positionId;

    /** @Column(type="boolean") */
    protected $isBoard;

    /** @Column(type="integer") */
    protected $year;
    
    public function __construct($memberId, $positionId, $isBoard, $year)
    {
        $this->memberId = $memberId;
        $this->positionId = $positionId;
        $this->isBoard = $isBoard;
        $this->year = $year;
    }

    public function jsonSerialize()
    {
        return [
            'memberId' => $this->getMemberId(),
            'positionId' => $this->getPositionId(),
            'isBoard' => $this->getIsBoard(),
            'year' => $this->getYear(),
        ];
    }

    public static function getSearchFields(): array {
        return ['memberId', 'positionId', 'isBoard', 'year'];
    }

    // Getters and setters
    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->memberId;
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