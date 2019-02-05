<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Entities\core\Ticket;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Core\TicketService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketController
{
    /**
     * @var TicketService
     */
    private $ticketService;
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
        $this->ticketService = $container->get(TicketService::class);
    }

    public function getTicket(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting ticket by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $ticket = $this->ticketService->getOne($args['id']);

        return $response->withJson($ticket, 200);
    }

    public function createTicket(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating ticket from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $ticket = $this->ticketService->create($body);
        $this->entityManager->commit();

        return $response->withJson($ticket, 201);
    }

    public function getPageTickets(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page tickets from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Ticket::getSearchFields());

        $tickets = $this->ticketService->getPage($params);
        $totalCount = $this->ticketService->getCount($params);

        $page = new Page($tickets, $params, $totalCount);
        return $response->withJson($page, 200);
    }

    public function deleteTicket(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting ticket from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->ticketService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }
}
