<?php

namespace KerosTest\Core;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class UserIntegrationTest extends AppTestCase
{
    public function testGetAllUsersShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/user',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(3, count($body->content));
    }

    public function testGetUserShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/user/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame("cbreeze", $body->username);
        $this->assertSame("hunter11", $body->password);
        $this->assertNotNull($body->lastConnectedAt);
        $this->assertSame("2018-07-10 10:06:10.000000", $body->createdAt->getDate());
        $this->assertSame(1, $body->id);
        $this->assertSame(false, $body->disabled);
        $this->assertSame("2022-05-15 08:06:26.000000", $body->expiresAt);
    }

    public function testGetCountryShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/user/100',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 400);
    }
}