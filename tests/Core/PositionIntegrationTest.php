<?php

namespace KerosTest\position;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class PositionIntegrationTest extends AppTestCase
{
    public function testGetAllPositionsShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(22, count($body));
        $this->assertNotNull(count($body[0]->id));
        $this->assertNotNull(count($body[0]->label));
    }

    public function testGetPositionWithoutPoleShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame($body->label, "Ancien membre");
        $this->assertNotNull($body->poleId);
    }

    public function testGetPositionWithPoleShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/3',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame($body->label, "Chargé d'affaires");
        $this->assertSame($body->poleId, 10);
    }

    public function testGetPositionShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/150',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 400);
    }
}