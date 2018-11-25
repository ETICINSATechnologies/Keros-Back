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
    public static function getConfig(): array {
        $dynamicConfig = parse_ini_file(__DIR__ . "/../settings.ini");
        $staticConfig = array(
            "ALG" => "HS256",
            "HASH" => "sha256",
            "isTesting" => (getenv("ENV") == "test")
        );

        return array_merge($dynamicConfig, $staticConfig);
    }
}