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
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(196, count($body));
        $this->assertNotNull(strlen($body[0]->id));
        $this->assertNotNull(strlen($body[0]->label));
    }

    public function testGetCountryShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/country/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame("Afghanistan", $body->label);
    }

    public function testGetCountryShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/country/197',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }
}