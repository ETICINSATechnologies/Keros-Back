<?php
namespace Keros\Tools;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Keros\Config\ConfigLoader;

class KerosEntityManager
{
    static function getEntityManager()
    {
        $kerosConfig = ConfigLoader::getConfig();
        $host = $kerosConfig['db']['host'];
        $port = $kerosConfig['db']['port'];
        $user = $kerosConfig['db']['user'];
        $pass = $kerosConfig['db']['pass'];
        $dbName = $kerosConfig['db']['dbName'];
        $isDevMode = $kerosConfig['db']['isDevMode'];

        $doctrineConfig = Setup::createAnnotationMetadataConfiguration(array(__DIR__), $isDevMode);

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
