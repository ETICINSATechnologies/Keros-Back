<?php

use Keros\Api\Cat\CatController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Keros\Config\ConfigLoader;
use Slim\App;

require dirname(__FILE__) . '/../../vendor/autoload.php';

$app = new App(['settings' => ConfigLoader::getConfig()]);

// Routing
$app->group("/api/v1", function() {
    $this->get("/health", function (Request $request, Response $response, array $args){
        $response->getBody()->write("OK");
        return $response;
    });

    $this->group('/cat', function () {
        $this->get("", CatController::class . ':getAllCats');
        $this->get('/{id:[0-9]+}', CatController::class . ':getCat');
        $this->post("", CatController::class . ':createCat');
    });
});

// Error Handler
$app->getContainer()['errorHandler'] = function ($c) {
    return new \Keros\Error\ErrorHandler();
};

try {
    $app->run();
} catch (Exception $e) {
    die("Error : " . $e->getMessage());
}