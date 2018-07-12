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
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(4, count($body->content));
        $this->assertNotNull(count($body->content[0]->id));
        $this->assertNotNull(count($body->content[0]->label));
    }
    public function testGetGenderShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/gender/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(1, count($body->content));
        $this->assertNotNull(count($body->content[0]->id));
        $this->assertNotNull(count($body->content[0]->label));
    }
    public function testGetGenderShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/gender/5',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 400);

        $body = json_decode($response->getBody());
        $this->assertEquals(1, count($body->content));

    }
}