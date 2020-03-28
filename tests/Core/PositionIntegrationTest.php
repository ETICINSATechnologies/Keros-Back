<?php

namespace KerosTest\position;

use Keros\Entities\Core\Pole;
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
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(22, count($body));
        $this->assertNotNull(strlen($body[0]->id));
        $this->assertNotNull(strlen($body[0]->label));
        //We don't want to have the Admin position returned here
        foreach ($body as $position){
            $this->assertNotEquals(27, $position->id);
        }
    }

    public function testGetPositionWithoutPoleShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame("Auditeur orga", $body->label);
        $this->assertNull($body->pole);
    }

    public function testGetPositionWithPoleShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/3',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(3, $body->id);
        $this->assertSame("Chargé d'affaires", $body->label);
        $this->assertEquals(9, $body->pole->id);
    }

    public function testGetPositionShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/150',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetAdminPositionShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/position/27',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(27, $body->id);
    }
}