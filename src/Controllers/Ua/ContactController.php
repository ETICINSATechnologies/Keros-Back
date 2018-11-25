<?php

namespace Keros\Controllers\Ua;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Contact;
use Keros\Services\Ua\ContactService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class ContactController
{
    /**
     * @var ContactService
     */
    private $contactService;

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
        $this->contactService = $container->get(ContactService::class);
    }

    public function getContact(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting contact by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $contact = $this->contactService->getOne($args["id"]);

        return $response->withJson($contact, 200);
    }

    public function getPageContact(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page contacts from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Contact::getSearchFields());

        $contact = $this->contactService->getPage($params);
        $totalCount = $this->contactService->getCount($params);

        $page = new Page($contact, $params, $totalCount);
        return $response->withJson($page, 200);
    }

    public function createContact(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating contact from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $this->entityManager->beginTransaction();
        $contact = $this->contactService->create($body);
        $this->entityManager->commit();

        return $response->withJson($contact, 201);
    }

    public function updateContact(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating contact from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $contact = $this->contactService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($contact, 200);
    }
}