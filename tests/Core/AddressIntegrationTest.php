<?php

namespace KerosTest\Address;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class AddressIntegrationTest extends AppTestCase
{
    public function testGetAllAddressesShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/address',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(5, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->line1));
        $this->assertNotNull(strlen($body->content[0]->line2));
        $this->assertNotNull(strlen($body->content[0]->postalCode));
        $this->assertNotNull(strlen($body->content[0]->country->label));
    }

    public function testGetAddressShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/address/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame("13 Rue du renard", $body->line1);
        $this->assertSame(null, $body->line2);
        $this->assertSame(69100, $body->postalCode);
        $this->assertSame("lyon", $body->city);
        $this->assertSame(1, $body->country->id);
    }

    public function testGetAddressShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/address/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }
}