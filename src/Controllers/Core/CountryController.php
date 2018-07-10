<?php

namespace Keros\Controllers\Core;

use Keros\Entities\Core\Country;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Core\CountryService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CountryController
{
    /**
     * @var CountryService
     */
    private $countryService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->countryService = new CountryService($container);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing one country if it exists
     * @throws KerosException if the validation fails
     */
    public function getCountry(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting country by ID from " . $request->getServerParams()["REMOTE_ADDR"]);
        $id = Validator::id($args['id']);

        $country = $this->countryService->getOne($id);
        if (!$country) {
            throw new KerosException("The country could not be found", 404);
        }
        return $response->withJson($country, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response containing all countries present in the DB
     * @throws KerosException if an unknown error occurs
     */
    public function getAllCountries(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get countries ");
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Country::getSearchFields());

        $countries = $this->countryService->getMany($params);
        return $response->withJson($countries, 200);
    }
}