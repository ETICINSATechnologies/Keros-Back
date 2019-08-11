<?php

namespace Keros\Tools\Helpers;

use Keros\Error\KerosException;

class ConsultantInscriptionHelper
{
    public static function getConsultantInscriptionFiles()
    {
        return self::$consultantInscriptionFiles;
    }

    public static function doesExist(string $document_name)
    {
        if ($document_name == null || !array_key_exists($document_name, self::$consultantInscriptionFiles)) {
            throw new KerosException("This file is not defined", 404);
            return null;
        } else {
            return $document_name;
        }
    }

    protected static $consultantInscriptionFiles = array(
        'documentIdentity' => array(
            'name' => 'documentIdentity',
            'set' => 'setDocumentIdentity',
            'get' => 'getDocumentIdentity',
            'directory_key' => 'INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY',
            'isRequired' => true,
            'validator' => 'requiredFileMixed',
            'string_validator' => 'requiredString',
        ),
        'documentScolaryCertificate' => array(
            'name' => 'documentScolaryCertificate',
            'set' => 'createDocumentScolaryCertificate',
            'get' => 'getDocumentScolaryCertificate',
            'directory_key' => 'INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY',
            'isRequired' => true,
            'validator' => 'requiredFileMixed',
            'string_validator' => 'requiredString',
        ),
        'documentRIB' => array(
            'name' => 'documentRIB',
            'set' => 'createDocumentRIB',
            'get' => 'getDocumentRIB',
            'directory_key' => 'INSCRIPTION_RIB_DIRECTORY',
            'isRequired' => true,
            'validator' => 'requiredFileMixed',
            'string_validator' => 'requiredString',
        ),
        'documentVitaleCard' => array(
            'name' => 'documentVitaleCard',
            'set' => 'createDocumentVitaleCard',
            'get' => 'getDocumentVitaleCard',
            'directory_key' => 'INSCRIPTION_VITALE_CARD_DIRECTORY',
            'isRequired' => true,
            'validator' => 'requiredFileMixed',
            'string_validator' => 'requiredString',
        ),
        'documentResidencePermit' => array(
            'name' => 'documentResidencePermit',
            'set' => 'createDocumentResidencePermit',
            'get' => 'getDocumentResidencePermit',
            'directory_key' => 'INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY',
            'isRequired' => false,
            'validator' => 'optionalFileMixed',
            'string_validator' => 'optionalString',
        ),
    );
}
