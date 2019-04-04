<?php

namespace Keros\Entities\Ua;



/**
 * @Entity
 * @Table(name="ua_study_document_type")
 */
class StudyDocumentType implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=255) */
    protected $location;

    /** @Column(type="boolean") */
    protected $isTemplatable;

    /** @Column(type="boolean") */
    protected $oneConsultant;

    /**
     * StudyDocumentType constructor.
     * @param $location
     * @param $isTemplatable
     * @param $oneConsultant
     */
    public function __construct($location, $isTemplatable, $oneConsultant)
    {
        $this->location = $location;
        $this->isTemplatable = $isTemplatable;
        $this->oneConsultant = $oneConsultant;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'location' => $this->getLocation(),
            'isTemplatable' => $this->getIsTemplatable(),
            'oneConsultant' => $this->getOneConsultant(),
        ];
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
    public function getOneConsultant()
    {
        return $this->oneConsultant;
    }

    /**
     * @param mixed $oneConsultant
     */
    public function setOneConsultant($oneConsultant): void
    {
        $this->oneConsultant = $oneConsultant;
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

    /**
     * @return mixed
     */
    public function getisTemplatable()
    {
        return $this->isTemplatable;
    }

    /**
     * @param mixed $isTemplatable
     */
    public function setIsTemplatable($isTemplatable): void
    {
        $this->isTemplatable = $isTemplatable;
    }

}