<?php

namespace Keros\Entities\Core;

/**
 * Class Document
 * @package Keros\Entities\Core
 * @Entity
 * @Table(name="core_document")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"core_document" = "Document", "ua_study_document" = "Keros\Entities\Ua\StudyDocument"})
 */
abstract class Document implements \JsonSerializable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="datetime")
     */
    protected $uploadDate;

    /** @Column(type="string", length=255) */
    protected $location;

    /**
     * Document constructor.
     * @param $date
     * @param $location
     */
    public function __construct($date, $location)
    {
        $this->uploadDate = $date;
        $this->location = $location;
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
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * @param mixed $uploadDate
     */
    public function setUploadDate($uploadDate): void
    {
        $this->uploadDate = $uploadDate;
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
}