<?php

namespace KerosTest\Address;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class PoleIntegrationTest extends AppTestCase
{
    public function testGetAllAddressesShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/address',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(3, count($body->content));
        $this->assertNotNull(count($body->content[0]->id));
        $this->assertNotNull(count($body->content[0]->line1));
        $this->assertNotNull(count($body->content[0]->line2));
        $this->assertNotNull(count($body->content[0]->postalCode));
        $this->assertNotNull(count($body->content[0]->country->id));
        $this->assertNotNull(count($body->content[0]->country->label));
    }

    public function testGetAddressShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/address/2',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame($body->label, "13 rue regard");
        $this->assertSame($body->label, null);
        $this->assertSame($body->postalCode, 69100);
        $this->assertSame($body->city, "lyon");
        $this->assertSame($body->country->id, 1);
        $this->assertSame($body->country->label, "Afghanistan");
    }

    public function testGetAddressShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/address/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 400);
    }
}