<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Entities\core\Address;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Core\AddressService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddressController
{
    /**
     * @var AddressService
     */
    private $addressService;
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
    }

    public function getAddress(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting address by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $address = $this->addressService->getOne($args['id']);

        return $response->withJson($address, 200);
    }

    public function createAddress(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating address from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $address = $this->addressService->create($body);
        $this->entityManager->commit();

        return $response->withJson($address, 201);
    }

    public function getPageAddresses(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page addresses from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Address::getSearchFields());

        $addresses = $this->addressService->getPage($params);
        $totalCount = $this->addressService->getCount($params);

        $page = new Page($addresses, $params, $totalCount);
        return $response->withJson($page, 200);
    }
}
