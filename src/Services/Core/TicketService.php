<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\TicketDataService;
use Keros\Entities\Core\Ticket;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class TicketService
{
    /**
     * @var TicketDataService
     */
    private $ticketDataService;
    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(ContainerInterface $container)
    {
        $this->ticketDataService = $container->get(TicketDataService::class);
        $this->memberService = $container->get(MemberService::class);
    }

    public function create(array $fields): Ticket
    {
        $userId = Validator::requiredId($fields["userId"]);
        $title = Validator::requiredString($fields["title"]);
        $message = Validator::requiredString($fields["message"]);
        $type = Validator::requiredString($fields["type"]);
        $status = Validator::requiredString($fields["status"]);

        $user = $this->memberService->getOne($userId);

        $ticket = new Ticket($user, $title, $message, $type, $status);
        $this->ticketDataService->persist($ticket);

        return $ticket;
    }


    public function getOne(int $id): Ticket
    {
        $id = Validator::requiredId($id);

        $ticket = $this->ticketDataService->getOne($id);
        if (!$ticket) {
            throw new KerosException("The ticket could not be found", 404);
        }
        return $ticket;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->ticketDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->ticketDataService->getCount($requestParameters);
    }

    public function delete(int $id)
    {
        $id = Validator::requiredId($id);
        $ticket = $this->getOne($id);

        $this->ticketDataService->delete($ticket);
    }
}