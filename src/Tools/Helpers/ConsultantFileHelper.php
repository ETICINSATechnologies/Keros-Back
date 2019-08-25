<?php

namespace Keros\Tools\Helpers;

use Keros\Error\KerosException;

class ConsultantFileHelper
{
    public static function getConsultantFiles()
    {
        return self::$consultantFiles;
    }

    public static function doesExist(string $document_name)
    {
        if ($document_name == null || !array_key_exists($document_name, self::$consultantFiles)) {
            throw new KerosException("This file is not defined", 404);
            return null;
        } else {
            return $document_name;
        }
    }

    protected static $consultantFiles = array(
        'documentIdentity' => array(
            'name' => 'documentIdentity',
            'set' => 'setDocumentIdentity',
            'get' => 'getDocumentIdentity',
            'directory_key' => 'CONSULTANT_IDENTITY_DOCUMENT_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
        'documentScolaryCertificate' => array(
            'name' => 'documentScolaryCertificate',
            'set' => 'createDocumentScolaryCertificate',
            'get' => 'getDocumentScolaryCertificate',
            'directory_key' => 'CONSULTANT_SCOLARY_CERTIFICATE_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
        'documentRIB' => array(
            'name' => 'documentRIB',
            'set' => 'createDocumentRIB',
            'get' => 'getDocumentRIB',
            'directory_key' => 'CONSULTANT_RIB_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
        'documentVitaleCard' => array(
            'name' => 'documentVitaleCard',
            'set' => 'createDocumentVitaleCard',
            'get' => 'getDocumentVitaleCard',
            'directory_key' => 'CONSULTANT_VITALE_CARD_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
        'documentResidencePermit' => array(
            'name' => 'documentResidencePermit',
            'set' => 'createDocumentResidencePermit',
            'get' => 'getDocumentResidencePermit',
            'directory_key' => 'CONSULTANT_RESIDENCE_PERMIT_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
        'documentCVEC' => array(
            'name' => 'documentCVEC',
            'set' => 'createDocumentCVEC',
            'get' => 'getDocumentCVEC',
            'directory_key' => 'CONSULTANT_CVEC_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
    );
}
