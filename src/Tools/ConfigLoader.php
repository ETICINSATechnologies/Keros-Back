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
        );

        return $absoluteConfig;
    }
}