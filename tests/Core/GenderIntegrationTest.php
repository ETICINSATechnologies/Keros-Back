<?php

namespace KerosTest\Gender;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class GenderIntegrationTest extends AppTestCase
{
    public function testGetAllGendersShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/gender',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(4, sizeof($body));

    }
     public function testGetGenderShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/gender/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame('H', $body->label);
    }
    public function testGetGenderShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/gender/5',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }
}