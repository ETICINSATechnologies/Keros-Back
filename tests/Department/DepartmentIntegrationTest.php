<?php

namespace KerosTest\Department;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class DepartmentIntegrationTest extends AppTestCase
{
    public function testGetAllDepartmentsShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/department',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(3, count($body->content));
        $this->assertNotNull(count($body->content[0]->id));
        $this->assertNotNull(count($body->content[0]->label));
        $this->assertNotNull(count($body->content[0]->name));


    }
}