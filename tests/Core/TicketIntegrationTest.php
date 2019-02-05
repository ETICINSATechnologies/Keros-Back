<?php

namespace KerosTest\Address;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class TicketIntegrationTest extends AppTestCase
{
    public function testGetAllTicketsShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/ticket',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(1, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->user->id));
        $this->assertNotNull(strlen($body->content[0]->title));
        $this->assertNotNull(strlen($body->content[0]->message));
        $this->assertNotNull(strlen($body->content[0]->type));
        $this->assertNotNull(strlen($body->content[0]->status));
    }

    public function testGetTicketShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/ticket/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame(1, $body->user->id);
        $this->assertSame("Impossible de changer son mot de passe", $body->title);
        $this->assertSame("Bonjour, je narrive pas à changer mon mot de passe", $body->message);
        $this->assertSame("Problème de compte", $body->type);
        $this->assertSame("En cours", $body->status);
    }

    public function testGetTicketShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/ticket/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }
}