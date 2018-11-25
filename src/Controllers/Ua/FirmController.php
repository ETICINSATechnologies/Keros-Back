<?php

namespace Keros\Controllers\Ua;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Firm;
use Keros\Services\Core\AddressService;
use Keros\Services\Ua\FirmService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FirmController
{
    /**
     * @var AddressService
     */
    private $addressService;
    /**
     * @var FirmService
     */
    private $firmService;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->addressService = $container->get(AddressService::class);
        $this->firmService = $container->get(FirmService::class);
    }

    public function getFirm(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting firm by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $firm = $this->firmService->getOne($args['id']);

        return $response->withJson($firm, 200);
    }

    public function createFirm(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating firm from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $firm = $this->firmService->create($body);
        $this->entityManager->commit();

        return $response->withJson($firm, 201);
    }

    public function updateFirm(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating firm from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $firm = $this->firmService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($firm, 200);
    }

    public function getPageFirms(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page firms from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Firm::getSearchFields());

        $firms = $this->firmService->getPage($params);
        $totalCount = $this->firmService->getCount($params);

        $page = new Page($firms, $params, $totalCount);
        return $response->withJson($page, 200);
    }
}
