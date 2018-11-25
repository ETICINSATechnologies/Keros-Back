<?php

namespace KerosTest;

use Keros\KerosApp;
use Keros\Tools\ConfigLoader;
use PDO;

require dirname(__FILE__) . '/../vendor/autoload.php';

class AppTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Slim\App
     */
    protected $app;
    /**
     * @var PDO
     */
    private static $db;
    private static $dbDataFileLocation;

    /**
     * Creates the test database for future tests, then sets up the static $db connected to it
     */
    public static function setUpBeforeClass()
    {
        AppTestCase::$dbDataFileLocation = join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'src', 'Tools', 'Database', 'kerosData.sql']);

        $config = ConfigLoader::getConfig();
        $host = $config['DB_HOST'];
        $port = $config['DB_PORT'];
        $user = $config['DB_USER'];
        $pass = $config['DB_PASS'];
        $dbName = $config['DB_NAME'];

        // Connect to the test database
        $dsn = "mysql:dbname=$dbName;host=$host;charset=UTF8;port=$port";
        AppTestCase::$db = new PDO($dsn, $user, $pass);
    }


    public static function tearDownAfterClass()
    {
        AppTestCase::$db = null;
    }

    /**
     * Runs the provisioning script (that deletes previous data)
     */
    protected function setUp()
    {
        $this->app = (new KerosApp())->getApp();
        $sql = file_get_contents(AppTestCase::$dbDataFileLocation);
        AppTestCase::$db->exec($sql);
    }

    /**
     * Test to ensure the class loads correctly
     */
    public function testShouldInit()
    {
        $this->assertTrue(true);
    }
}