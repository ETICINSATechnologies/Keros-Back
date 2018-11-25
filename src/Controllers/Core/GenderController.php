<?php


namespace Keros\Controllers\Core;

use Keros\DataServices\Core\GenderDataService;
use Keros\Services\Core\GenderService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GenderController
{
    /**
     * @var GenderService
     */
    private $genderService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->genderService = $container->get(GenderService::class);
    }

    public function getGender(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting gender by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $gender = $this->genderService->getOne($args['id']);

        return $response->withJson($gender, 200);
    }

    public function getAllGenders(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all genders from " . $request->getServerParams()["REMOTE_ADDR"]);

        $genders = $this->genderService->getAll();

        return $response->withJson($genders, 200);
    }

}
