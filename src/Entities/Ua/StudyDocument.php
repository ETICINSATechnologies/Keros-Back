<?php

namespace Keros\Entities\Ua;

use JsonSerializable;
use Keros\Entities\Core\Template;

/**
 * Class StudyDocument
 * @package Keros\Entities
 * @Entity
 * @Table(name="ua_study_document")
 */
class StudyDocument implements JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Ua\Study")
     * @JoinColumn(name="studyId", referencedColumnName="id")
     **/
    protected $study;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Core\Template")
     * @JoinColumn(name="templateId", referencedColumnName="id")
     **/
    protected $template;

    /**
     * @Column(type="datetime")
     */
    protected $date;

    /** @Column(type="string", length=255) */
    protected $name;

    /** @Column(type="string", length=255) */
    protected $location;

    /**
     * StudyDocument constructor.
     * @param $study
     * @param $template
     * @param $date
     * @param $name
     * @param $location
     */
    public function __construct($study, $template, $date, $name, $location)
    {
        $this->study = $study;
        $this->template = $template;
        $this->date = $date;
        $this->name = $name;
        $this->location = $location;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'study' => $this->getStudy(),
            'template' => $this->getTemplate(),
            'date' => $this->getDate(),
            'name' => $this->getName(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Study
     */
    public function getStudy()
    {
        return $this->study;
    }

    /**
     * @param mixed $study
     */
    public function setStudy($study): void
    {
        $this->study = $study;
    }

    /**
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
