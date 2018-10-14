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
        $address=array(
            "line1"=>"20 avenue albert Eistein",
            "line2"=>"residence g",
            "city"=>"lyon",
            "postalCode"=>69100,
            "countryId"=>1
        );

        $post_body = array(
            "username" => "username",
            "firstName"=>"firstname",
            "lastName"=>"lastname",
            "genderId"=>1,
            "email"=>"fakeEmail@gmail.com",
            "birthday"=>"1975-12-01",
            "telephone"=>"0033675385495",
            "address"=>$address,

            "schoolYear"=>1,
            "departmentId"=>1,
            "password"=>"password",
            "disabled"=>null
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
        $this->assertSame("username", $body->username);
        $this->assertSame("lastname", $body->lastName);
    }
    public function testPutMemberShouldReturn200()
    {
        $address=array(
            "line1"=>"20 avenue albert Eistein",
            "line2"=>"residence g",
            "city"=>"lyon",
            "postalCode"=>69100,
            "countryId"=>1
        );
        $positionIds=array(1,2);
        $post_body = array(
            "username" => "username",
            "firstName"=>"firstname",
            "lastName"=>"lastname",
            "genderId"=>1,
            "email"=>"fakeEmail@gmail.com",
            "birthday"=>"1975-12-01",
            "telephone"=>"0033675385495",
            "address"=>$address,
            "positionIds" => $positionIds,
            "schoolYear"=>1,
            "departmentId"=>1,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 201);

        $body = json_decode($response->getBody());
        $this->assertSame("username", $body->username);
        $this->assertSame("lastname", $body->lastName);
    }
}