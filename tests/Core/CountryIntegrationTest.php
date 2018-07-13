<?php

namespace KerosTest\country;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class CountryIntegrationTest extends AppTestCase
{
    public function testGetAllCountriesShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/country',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(196, count($body->content));
    }

    public function testGetCountryShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/country/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame($body->label, "Afghanistan");
    }

    public function testGetCountryShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/country/197',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 404);
    }
}