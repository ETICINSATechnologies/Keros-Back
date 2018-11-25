<?php

namespace Keros\Controllers\Core;

use Keros\Services\Core\CountryService;
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
        $this->logger = $container->get(Logger::class);
        $this->countryService = $container->get(CountryService::class);
    }

    public function getCountry(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting country by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $country = $this->countryService->getOne($args["id"]);

        return $response->withJson($country, 200);
    }

    public function getAllCountries(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get countries " . $request->getServerParams()["REMOTE_ADDR"]);

        $countries = $this->countryService->getAll();

        return $response->withJson($countries, 200);
    }
}
