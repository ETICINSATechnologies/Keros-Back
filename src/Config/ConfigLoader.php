<?php
namespace Keros\Config;

class ConfigLoader
{
    public static function getConfig(){
        // TODO Check with $_ENV for environement
        $config = self::getDevelopmentConfig();

        // General configuration
        $config['addContentLengthHeader'] = true;

        return $config;
    }

    private static function getDevelopmentConfig(){
        $config['displayErrorDetails'] = true;

        $config['db']['host']   = 'localhost';
        $config['db']['user']   = 'root';
        $config['db']['pass']   = 'root';
        $config['db']['dbname'] = 'keros';

        return $config;
    }
}