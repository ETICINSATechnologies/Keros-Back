<?php
namespace Keros\Api\Cat;

use Exception;
use Keros\Entities\Cat;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CatController
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function getCat(Request $request, Response $response, array $args){
        $id = Validator::id($args['id']);
        $cat = Cat::getOne($id);
        $response->getBody()->write(json_encode($cat));
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function createCat(Request $request, Response $response, array $args){
        $body = $request->getParsedBody();

        $name = Validator::name($body["name"]);
        $height = Validator::float( $body["height"]);

        $cat = new Cat(null, $name, $height);
        $cat->create();
        $response->getBody()->write(json_encode($cat));
        return $response;
    }

    public function getAllCats(Request $request, Response $response, array $args){
        $params = $request->getQueryParams();
        $page = isset($params["page"]) ? $params["page"] : 0;
        $page = Validator::page($page);
        $cats = Cat::getAll($page);
        $response->getBody()->write(json_encode($cats));
        return $response;
    }
}