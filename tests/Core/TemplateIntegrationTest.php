<?php

namespace KerosTest\Core;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class TemplateIntegrationTest extends AppTestCase
{
    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
   /* public function testPostTemplateShouldReturn201()
    {
        $post_body = array(
            "name" => "testInsert",
            "location" => "ici",
            "typeId" => 1,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/template',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame("testInsert", $body->name);
    }
*/
    public function testGetTemplateShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/template/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame("testGet", $body->name);
        $this->assertSame(1, $body->id);
    }

    public function testGetTemplateShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/template/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDeleteTemplateShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/template/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDeleteTemplateShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/template/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(204, $response->getStatusCode());
    }
}