<?php


namespace Keros;


use http\Env\Request;
use http\Env\Response;
use Keros\Config\ConfigLoader;
use Keros\Controllers\Core\AddressController;
use Keros\Controllers\Core\GenderController;
use Keros\Controllers\Cat\CatController;
use Keros\Controllers\Ua\FirmTypeController;
use Keros\Controllers\Core\DepartmentController;
use Keros\Controllers\Core\CountryController;
use Keros\Controllers\Core\UserController;
use Keros\Error\ErrorHandler;
use Keros\Tools\KerosEntityManager;
use Keros\Tools\Logger;

/**
 * Class KerosApp - Main class ran by index.php. Used to configure
 * the Slim app
 * @package Keros
 */
class KerosApp
{
    /**
     * Stores an instance of the Slim application.
     * @var \Slim\App
     */
    private $app;

    /**
     * KerosApp constructor. Configures the Slim App
     */
    public function __construct()
    {
        $app = new \Slim\App(['settings' => ConfigLoader::getConfig()]);

        $app->group("/api/v1", function () {
            $this->get("/health", function (Request $request, Response $response, array $args) {
                $response->getBody()->write("OK");
                return $response;
            });

            $this->group('/cat', function () {
                $this->get("", CatController::class . ':getPageCats');
                $this->get('/{id:[0-9]+}', CatController::class . ':getCat');
                $this->post("", CatController::class . ':createCat');
            });

            $this->group('/ua', function () {
                $this->group('/firm-type', function() {
                    $this->get("", FirmTypeController::class . ':getAllFirmType');
                    $this->get("/{id:[0-9]+}", FirmTypeController::class . ':getFirmType');
                });
            });

            $this->group('/core', function () {
              
                $this->group('/department', function() {
                    $this->get("", DepartmentController::class . ':getAllDepartments');
                    $this->get("/{id:[0-9]+}", DepartmentController::class . ':getDepartment');
                });
              
                $this->group('/gender', function() {
                    $this->get("", GenderController::class . ':getAllGenders');
                    $this->get("/{id:[0-9]+}", GenderController::class . ':getGender');
                });
                
                $this->group('/country', function() {
                    $this->get("", CountryController::class . ':getAllCountries');
                    $this->get("/{id:[0-9]+}", CountryController::class . ':getCountry');
                });

                $this->group('/address', function() {
                    $this->get("", AddressController::class . ':getPageAddresses');
                    $this->get('/{id:[0-9]+}', AddressController::class . ':getAddress');
                    $this->post("", AddressController::class . ':createAddress');
                });

                $this->group('/user', function() {
                    $this->get("", UserController::class . ':getPageUsers');
                    $this->get('/{id:[0-9]+}', UserController::class . ':getUser');
                    $this->post("", UserController::class . ':createUser');
                });
             
            });
        });


        $app->getContainer()['entityManager'] = function ($c) {
            return KerosEntityManager::getEntityManager();
        };

        $app->getContainer()['errorHandler'] = function ($c) {
            return new ErrorHandler($c);
        };

        $app->getContainer()['logger'] = function ($c) {
            return Logger::createLogger();
        };

        $this->app = $app;
    }

    /**
     * Get an instance of the Slim app
     * @return \Slim\App
     */
    public function getApp()
    {
        return $this->app;
    }
}
