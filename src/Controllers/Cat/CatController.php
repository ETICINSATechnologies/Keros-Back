<?php
namespace Keros\Controllers\Cat;

use Exception;
use Keros\Entities\Cat;
use Keros\Error\KerosException;
use Keros\Services\Cat\CatService;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CatController
{
    /**
     * @var ContainerInterface The Slim app container interface
     */
    private $container;
    private $catService;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->catService = new CatService();
    }

    /**
     * @return Response containing one cat if it exists
     * @throws KerosException if the validation fails
     */
    public function getCat(Request $request, Response $response, array $args){
        $id = Validator::id($args['id']);

        $cat = $this->catService->getOne($id);
        if(!$cat){
            throw new KerosException("The cat could not be found", 404);
        }

        $response->getBody()->write(json_encode($cat));
        return $response;
    }

    /**
     * @return Response containing the created cat
     * @throws KerosException if the validation fails or the cat cannot be created
     */
    public function createCat(Request $request, Response $response, array $args){
        $body = $request->getParsedBody();

        $name = Validator::name($body["name"]);
        $height = Validator::float( $body["height"]);

        $cat = new Cat(null, $name, $height);
        $cat = $this->catService->create($cat);
        $response->getBody()->write(json_encode($cat));
        return $response;
    }

    /**
     * @return Response containing a page of cats
     * @throws KerosException if the validation fails
     */
    public function getAllCats(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();
        $page = isset($params["page"]) ? $params["page"] : 0;

        $page = Validator::page($page);

        $cats = $this->catService->getAll($page);
        $response->getBody()->write(json_encode($cats));
        return $response;
    }
}