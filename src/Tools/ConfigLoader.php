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
        $config = parse_ini_file(__DIR__ . "/../settings.ini");
        return $config;
    }
}