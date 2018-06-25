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

        if(isset($_ENV["ENV"]) && $_ENV["ENV"] == "test") {
            $config = self::getTestConfig();
        }
        else if (isset($_ENV["ENV"]) && $_ENV["ENV"] == "travis"){
            $config = self::getTravisCIConfig();
        }
        else {
            $config = self::getDevelopmentConfig();
        }
        // General configuration
        $config['addContentLengthHeader'] = true;

        return $config;
    }

    private static function getDevelopmentConfig(): array {
        $config['displayErrorDetails'] = true;

        $config['db']['host']   = 'localhost';
        $config['db']['port']   = '3306';
        $config['db']['user']   = 'root';
        $config['db']['pass']   = 'root';
        $config['db']['dbName'] = 'keros';
        $config['db']['isDevMode'] = true;

        return $config;
    }

    private static function getTestConfig(): array {
        $config['displayErrorDetails'] = true;

        $config['db']['host']   = 'localhost';
        $config['db']['port']   = '3306';
        $config['db']['user']   = 'root';
        $config['db']['pass']   = 'root';
        $config['db']['dbName'] = 'keros_test';
        $config['db']['isDevMode'] = true;

        return $config;
    }

    private static function getTravisCIConfig(): array {
        $config['displayErrorDetails'] = true;

        $config['db']['host']   = 'localhost';
        $config['db']['port']   = '3306';
        $config['db']['user']   = 'root';
        $config['db']['pass']   = '';
        $config['db']['dbName'] = 'keros_test';
        $config['db']['isDevMode'] = true;

        return $config;
    }
}