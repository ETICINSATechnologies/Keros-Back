<?php


namespace Keros\Entities\Treso;


use DateTime;

/**
 * @Entity
 * Class Turnover
 * @package Keros\Entities\Treso
 * @Table(name="treso_turnover")
 */
class Turnover implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $date;

    /** @Column(type="float") */
    protected $revenue;

    /**
     * Turnover constructor.
     * @param $date
     * @param $revenue
     */
    public function __construct($date,$revenue)
    {
        $this->date=$date;
        $this->revenue=$revenue;
    }


    public function jsonSerialize()
    {
        return [
            'id'=>$this->getId(),
            'date'=>$this->getDateFormatted(),
            'revenue'=>$this->getRevenue(),
        ];
    }

    public static function getSearchFields(): array
    {
        return ['date'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDateFormatted()
    {
        return $this->date->format('Y-m-d');
    }

    /**
     * @return float
     */
    public function getRevenue()
    {
        return $this->revenue;
    }
    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date=$date;
    }
    /**
     * @param  float $revenue
     */
    public function setRevenue($revenue)
    {
        $this->revenue=$revenue;
    }
}