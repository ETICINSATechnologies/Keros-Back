<?php

namespace KerosTest\Consultant;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class ConsultantIntegrationTest extends AppTestCase
{
    public function testLikeSearchConsultantShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?search=Marah',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(2, $body->content[0]->id);
        $this->assertSame("Marah", $body->content[0]->firstName);
        $this->assertSame("Cool", $body->content[0]->lastName);
    }

    public function testSearchConsultantShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?firstName=Marah',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(2, $body->content[0]->id);
        $this->assertSame("Marah", $body->content[0]->firstName);
        $this->assertSame("Cool", $body->content[0]->lastName);
    }

    public function testPutConnectedConsultantEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 2);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testPutConnectedConsultantShouldReturn200()
    {
        $put_body = array(
            "username" => "newusername",
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
            "company" => "Amazon",
            "profilePicture" => "http://image.png"
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 2);
        $req = $req->withParsedBody($put_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("firstName", $body->firstName);
        $this->assertSame("lastName", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertSame("http://image.png", $body->profilePicture);
        $this->assertNotNull($body->address->id);
    }

    public function testDeleteConsultantShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteInvalidConsultantShouldReturn404(){
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/consultant/10',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());

    }

    public function testGetAllConsultantsShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(2, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->username));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->gender->id));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->birthday));
        $this->assertNotNull(strlen($body->content[0]->address->id));
    }

    public function testGetConsultantShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(2, $body->id);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("Marah", $body->firstName);
        $this->assertSame("Cool", $body->lastName);
        $this->assertSame("1976-10-27", $body->birthday);
        $this->assertSame("+332541541", $body->telephone);
        $this->assertSame("fake.mail2@fake.com", $body->email);
        $this->assertSame("Amazon", $body->company);
    }

    public function testGetConsultantShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostConsultantShouldReturn200()
    {
        $post_body = array(
            "username" => "newusername",
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
            "company" => "Amazon",
            "profilePicture" => "http://image.png"
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/consultant',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(201, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("lastname", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertSame("http://image.png", $body->profilePicture);
    }
    public function testPutConsultantShouldReturn200()
    {
        $post_body = array(
            "username" => "newusername",
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
            "positions" => [
                array(
                    "id" => 3,
                    "year" => 2018,
                    "isBoard" => true
                ),
                array(
                    "id" => 4,
                    "year" => 2019,
                    "isBoard" => false
                )
            ],
            "company" => "Amazon",
            "profilePicture" => "http://image.png"
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("firstName", $body->firstName);
        $this->assertSame("lastName", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertSame("http://image.png", $body->profilePicture);
        $this->assertNotNull($body->address->id);
    }

    public function testPutConsultantEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(400, $response->getStatusCode());
    }
}