<?php
namespace Keros\Config;

/**
 * Class ConfigLoader. Used for determining which configuration is used for which environement
 * @package Keros\Config
 */
class ConfigLoader
{
    /**
     * Gets the needed configuration for the current environement
     * @return array a configuration instance
     */
    public static function getConfig(): array {
        // TODO Check with $_ENV for environement
        $config = self::getDevelopmentConfig();

        // General configuration
        $config['addContentLengthHeader'] = true;

        return $config;
    }

    /**
     * @return array The development configuration
     */
    private static function getDevelopmentConfig(): array {
        $config['displayErrorDetails'] = true;

        $config['db']['host']   = 'localhost';
        $config['db']['user']   = 'root';
        $config['db']['pass']   = 'root';
        $config['db']['dbname'] = 'keros';

        return $config;
    }
}