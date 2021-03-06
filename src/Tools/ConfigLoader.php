<?php

namespace Keros\Tools;

/**
 * Class ConfigLoader. Used for determining which configuration is used for which environment
 * @package Keros\Config
 */
class ConfigLoader
{
    /**
     * Gets the needed configuration for the current environment
     * @return array a configuration instance
     */
    public static function getConfig(): array
    {
        $dynamicConfig = parse_ini_file(__DIR__ . "/../settings.ini");
        $staticConfig = array(
            "ALG" => "HS256",
            "HASH" => "sha256",
            "isTesting" => (getenv("ENV") == "test")
        );

        if ($dynamicConfig['RELATIVE_PATH'] == true) {
            $dynamicConfig = array_merge($dynamicConfig, self::absolutePathFromConfig($dynamicConfig));
        }

        return array_merge($dynamicConfig, $staticConfig);
    }

    private static function absolutePathFromConfig(array $config)
    {
        $absoluteDirPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
        $absoluteConfig = array(
            'DOCUMENT_DIRECTORY' => $absoluteDirPath . $config['DOCUMENT_DIRECTORY'],
            'DOCUMENT_TYPE_DIRECTORY' => $absoluteDirPath . $config['DOCUMENT_TYPE_DIRECTORY'],
            'TEMPORARY_DIRECTORY' => $absoluteDirPath . $config['TEMPORARY_DIRECTORY'],
            'STUDY_DOCUMENT_DIRECTORY' => $absoluteDirPath . $config['STUDY_DOCUMENT_DIRECTORY'],
            'FACTURE_DOCUMENT_DIRECTORY' => $absoluteDirPath . $config['FACTURE_DOCUMENT_DIRECTORY'],
            'MEMBER_PHOTO_DIRECTORY' => $absoluteDirPath . $config['MEMBER_PHOTO_DIRECTORY'],
            'INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY' => $absoluteDirPath . $config['INSCRIPTION_IDENTITY_DOCUMENT_DIRECTORY'],
            'INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY' => $absoluteDirPath . $config['INSCRIPTION_SCOLARY_CERTIFICATE_DIRECTORY'],
            'INSCRIPTION_RIB_DIRECTORY' => $absoluteDirPath . $config['INSCRIPTION_RIB_DIRECTORY'],
            'INSCRIPTION_VITALE_CARD_DIRECTORY' => $absoluteDirPath . $config['INSCRIPTION_VITALE_CARD_DIRECTORY'],
            'INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY' => $absoluteDirPath . $config['INSCRIPTION_RESIDENCE_PERMIT_DIRECTORY'],
            'INSCRIPTION_CVEC_DIRECTORY' => $absoluteDirPath . $config['INSCRIPTION_CVEC_DIRECTORY'],
            'CONSULTANT_IDENTITY_DOCUMENT_DIRECTORY' => $absoluteDirPath . $config['CONSULTANT_IDENTITY_DOCUMENT_DIRECTORY'],
            'CONSULTANT_SCOLARY_CERTIFICATE_DIRECTORY' => $absoluteDirPath . $config['CONSULTANT_SCOLARY_CERTIFICATE_DIRECTORY'],
            'CONSULTANT_RIB_DIRECTORY' => $absoluteDirPath . $config['CONSULTANT_RIB_DIRECTORY'],
            'CONSULTANT_VITALE_CARD_DIRECTORY' => $absoluteDirPath . $config['CONSULTANT_VITALE_CARD_DIRECTORY'],
            'CONSULTANT_RESIDENCE_PERMIT_DIRECTORY' => $absoluteDirPath . $config['CONSULTANT_RESIDENCE_PERMIT_DIRECTORY'],
            'CONSULTANT_CVEC_DIRECTORY' => $absoluteDirPath . $config['CONSULTANT_CVEC_DIRECTORY'],
            'MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY' => $absoluteDirPath . $config['MEMBER_INSCRIPTION_DOCUMENT_DIRECTORY']
        );

        return $absoluteConfig;
    }
}