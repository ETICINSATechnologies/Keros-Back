<?php

namespace Keros\Tools\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Keros\Tools\ConfigLoader;

class EntityManagerBuilder
{
    static function getEntityManager()
    {
        $kerosConfig = ConfigLoader::getConfig();
        $host = $kerosConfig['DB_HOST'];
        $port = $kerosConfig['DB_PORT'];
        $user = $kerosConfig['DB_USER'];
        $pass = $kerosConfig['DB_PASS'];
        $dbName = $kerosConfig['DB_NAME'];
        $devMode = $kerosConfig['DEV_MODE'];

        $cacheDir = dirname(__FILE__).'/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir);
        }
        $doctrineConfig = Setup::createAnnotationMetadataConfiguration(array(__DIR__), $devMode, $cacheDir);
        $doctrineConfig->setAutoGenerateProxyClasses(true);     

        $conn = array(
            'url' => "pdo-mysql://$user:$pass@$host:$port/$dbName?charset=UTF8",
        );

        try {
            return EntityManager::create($conn, $doctrineConfig);
        } catch (ORMException $e) {
            die($e->getMessage());
        }
    }
}
