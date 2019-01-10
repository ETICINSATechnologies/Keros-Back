<?php

namespace KerosTest\Ticket;

use Keros\Entities\core\Ticket;
use Keros\Entities\Core\User;
use PHPUnit\Framework\TestCase;


class TicketTest extends TestCase
{
    public function testNewTicketShouldBeInstanceOfTicket()
    {
        $this->assertInstanceOf(Ticket::class,
                                new Ticket(1,"haha","hoho","hehe","hihi"));
    }

    public function testTicketShouldCreateWithParams()
    {
        $ticket = new Ticket(1,"haha","hoho","hehe","hihi");

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals(1, $ticket->getUser());
        $this->assertEquals("haha", $ticket->getTitle());
        $this->assertEquals("hoho", $ticket->getMessage());
        $this->assertEquals("hehe", $ticket->getType());
        $this->assertEquals("hihi", $ticket->getStatus());
        $this->assertNull($ticket->getId());
    }
}