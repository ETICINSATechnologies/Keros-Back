<?php

namespace KerosTest\Member;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class MemberIntegrationTest extends AppTestCase
{
    public function testGetAllMembersShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(3, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->username));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->genderId));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->birthday));
        $this->assertNotNull(strlen($body->content[0]->addressId));

    }

    public function testGetMemberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertSame($body->id, 1);
        $this->assertSame($body->genderId, 1);
        $this->assertSame($body->firstName, "Conor");
        $this->assertSame($body->lastName, "Breeze");
        $this->assertSame($body->birthday, "1975-12-25");
        $this->assertSame($body->telephone, "+332541254");
        $this->assertSame($body->email, "fake.mail@fake.com");
        $this->assertSame($body->addressId, 2);
    }

    public function testGetMemberShouldReturn400()
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

    public function testPostMemberShouldReturn200()
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
            "positionIds" => [
                1,
                2
            ]
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 201);

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->id);
        $this->assertSame("username", $body->username);
        $this->assertSame("lastname", $body->lastName);
        $this->assertSame(1, $body->genderId);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->departmentId);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertNotNull($body->addressId);
        $this->assertContains(1, $body->positionIds);
        $this->assertContains(2, $body->positionIds);
    }
    public function testPutMemberShouldReturn200()
    {
        $post_body = array(
            "id" => 1,
            "username" => "username",
            "password" => "password",
            "firstName" => "firstName",
            "lastName" => "lastName",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "address" => [
                "line1" => "20 avenue albert Einstein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "schoolYear" => 1,
            "departmentId" => 1,
            "positionIds" => [
                1,
                3
            ]
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());

        $this->assertNotNull($body->id);
        $this->assertSame("username", $body->username);
        $this->assertSame("firstName", $body->firstName);
        $this->assertSame("lastName", $body->lastName);
        $this->assertSame(1, $body->genderId);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->departmentId);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertNotNull($body->addressId);
        $this->assertContains(1, $body->positionIds);
        $this->assertContains(3, $body->positionIds);
    }
}