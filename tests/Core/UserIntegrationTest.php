<?php

namespace KerosTest\User;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class UserIntegrationTest extends AppTestCase
{
    public function testPostMemberWithExistingUsernameShouldReturn200()
    {
        $post_body = array(
            "username" => "username",
            "password" => "password",
            "firstName" => "firstname",
            "lastName" => "lastname",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "departmentId" => 1,
            "schoolYear" => 1,
            "address" => [
                "line1" => "20 avenue albert Eistein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "disabled" => null,
            "positions" => [
                array(
                    "id" => 3,
                    "year" => 2018,
                    "isBoard" => true
                ),
                array(
                    "id" => 3,
                    "year" => 2019,
                    "isBoard" => false
                )
            ],
            "company" => "Amazon",
            "droitImage" => false
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withAttribute("userId", 6);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(201, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->id);
        $this->assertSame("username1", $body->username);
    }
}