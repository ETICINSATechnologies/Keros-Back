<?php

namespace KerosTest\Ua;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class ContactIntegrationTest extends AppTestCase
{
    public function testGetAllContactShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame($response->getStatusCode(), 200);
        $body = json_decode($response->getBody());

        $this->assertEquals(count($body->content), 4);
    }

    public function testGetContactShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals($body->firstName, "Alexandre");
        $this->assertEquals($body->lastName, "Lang");
        $this->assertNotNull($body->gender);
        $this->assertNotNull($body->firm);
        $this->assertEquals($body->email, "alexandre.lang@etic.com");

        $this->assertNull($body->telephone);
        $this->assertEquals($body->cellphone, "0033111111111");
        $this->assertEquals($body->position,"C'est une bonne position, ça scribe?");
        $this->assertEquals($body->notes, "this is a note");
        $this->assertEquals($body->old, true);
    }

    public function testGetContactShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact/6',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostContactOnlyWithOnlyRequiredParamsShouldReturn201()
    {
        $post_body = array(
            "firstName" => "lolo",
            "lastName" => "momo",
            "genderId" => 1,
            "firmId" => 2,
            "email" => "lolo.momo@gmail.com",
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/ua/contact',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame($body->firstName,"lolo");
        $this->assertSame($body->lastName, "momo");
    }

    public function testPutContactShouldReturn200()
    {

        $post_body = array(
            "firstName" => "lolo",
            "lastName" => "momo",
            "genderId" => 1,
            "firmId" => 2,
            "email" => "lolomomo@gmail.com",
            "telephone" => "0033675985495",
            "cellphone" => "0033175985495",
            "position" => "chef de projet",
            "notes" => "RAS",
            "old" => true
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame("lolo", $body->firstName);
    }

    public function testPutContactWithEmptyBodyShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
    }
}