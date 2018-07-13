<?php

namespace KerosTest\Firm_type;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class Firm_typeIntegrationTest extends AppTestCase
{
    public function testGetAllFirm_typeShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/firm-type',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);
        $body = json_decode($response->getBody());
        $this->assertEquals(9, sizeof($body));
    }
    public function testGetFirm_typeShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/firm-type/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame($body->label, "Administration");
    }
    public function testGetFirm_TypeShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '\'/api/v1/ua/firm-type/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 400);
    }
}