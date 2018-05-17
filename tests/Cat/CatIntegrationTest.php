<?php

use Keros\KerosApp;
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class CatIntegrationTest extends TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = (new KerosApp())->get();
    }

    // TODO find a way to fake DB for better testing
    public function testGetAllCatsShouldReturn200() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/api/v1/cat',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertNotEmpty((string)$response->getBody());
    }
}