<?php

namespace Keros\Entities\Ua;

use JsonSerializable;
use Keros\Entities\Core\Document;

/**
 * Class StudyDocument
 * @package Keros\Entities
 * @Entity
 * @Table(name="ua_study_document")
 */
class StudyDocument extends Document implements JsonSerializable
{

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Ua\Study")
     * @JoinColumn(name="studyId", referencedColumnName="id")
     **/
    protected $study;

    /**
     * @ManyToOne(targetEntity="Keros\Entities\Ua\StudyDocumentType")
     * @JoinColumn(name="studyDocumentTypeId", referencedColumnName="id")
     **/
    protected $studyDocumentType;

    /**
     * StudyDocument constructor.
     * @param $date
     * @param $location
     * @param $study
     * @param $studyDocumentType
     */
    public function __construct($date, $location, $study, $studyDocumentType)
    {
        parent::__construct($date, $location);
        $this->study = $study;
        $this->studyDocumentType = $studyDocumentType;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'study' => $this->getStudy(),
            'studyDocumentType' => $this->getStudyDocumentType(),
            'date' => $this->getUploadDate(),
            'location' => $this->getLocation(),
        ];
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getStudyDocumentType()
    {
        return $this->studyDocumentType;
    }

    /**
     * @param mixed $studyDocumentType
     */
    public function setStudyDocumentType($studyDocumentType): void
    {
        $this->studyDocumentType = $studyDocumentType;
    }

}
