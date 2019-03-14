<?php

namespace KerosTest\FactureType;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class FactureTypeIntegrationTest extends AppTestCase
{
    public function testGetAllFactureTypesShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/facture-types',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(4, sizeof($body));
    }
}