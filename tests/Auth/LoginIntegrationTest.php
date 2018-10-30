<?php

namespace KerosTest\Login;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class LoginIntegrationTest extends AppTestCase
{
    public function testLoginShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/auth/login',
        ]);

        $postBody = array(
            "username" => "cbreeze",
            "password" => "hunter11"
        );

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($postBody);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertContains("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.", $body->token);
    }

    public function testLoginShouldReturn401()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/auth/login',
        ]);

        $postBody = array(
            "username" => "cbreeze",
            "password" => "hunter12"
        );

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($postBody);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(401, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals($body->message, "Authentication failed");
    }
}