<?php


namespace Keros;


use http\Env\Request;
use http\Env\Response;
use Keros\Config\ConfigLoader;
use Keros\Controllers\Cat\CatController;
use Keros\Error\ErrorHandler;

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
                $this->get("", CatController::class . ':getAllCats');
                $this->get('/{id:[0-9]+}', CatController::class . ':getCat');
                $this->post("", CatController::class . ':createCat');
            });
        });


        $app->getContainer()['errorHandler'] = function ($c) {
            return new ErrorHandler();
        };


        $this->app = $app;
    }

    /**
     * Get an instance of the Slim app
     * @return \Slim\App
     */
    public function get()
    {
        return $this->app;
    }
}
