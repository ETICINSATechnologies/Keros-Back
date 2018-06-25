<?php

namespace Keros\Controllers\Cat;

use Keros\Entities\Cat\Cat;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Cat\CatService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CatController
{
    /**
     * @var CatService
     */
    private $catService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->catService = new CatService($container);
    }

    /**
     * @return Response containing one cat if it exists
     * @throws KerosException if the validation fails
     */
    public function getCat(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting cat by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $cat = $this->catService->getOne($id);
        if (!$cat) {
            throw new KerosException("The cat could not be found", 404);
        }
        return $response->withJson($cat, 200);
    }

    /**
     * @return Response containing the created cat
     * @throws KerosException if the validation fails or the cat cannot be created
     */
    public function createCat(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating cat from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $name = Validator::name($body["name"]);
        $height = Validator::float($body["height"]);

        $cat = new Cat($name, $height);
        $this->catService->create($cat);

        return $response->withJson($cat, 201);
    }

    /**
     * @return Response containing a page of cats
     * @throws KerosException if an unknown error occurs
     */
    public function getPageCats(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page cats from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Cat::getSearchFields());

        $cats = $this->catService->getMany($params);
        $totalCount = $this->catService->getCount($params);

        $page = new Page($cats, $params, $totalCount);

        return $response->withJson($page, 200);
    }
}