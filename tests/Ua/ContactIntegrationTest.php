<?php

namespace KerosTest\Ua;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class ContactIntegrationTest extends AppTestCase
{
    public function testFirmIdWithMainContact()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact?firmId=1',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(6, sizeof($body->content));
        $this->assertSame(4, $body->content[0]->id);
        $this->assertSame(2, $body->content[1]->id);
        $this->assertSame(3, $body->content[2]->id);
    }

    public function testSearchLikeContactShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact?lastName=Lan',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(1, $body->content[0]->id);
    }

    public function testSearchContactShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact?lastName=Lang',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(1, $body->content[0]->id);
    }

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

        $this->assertEquals(count($body->content), 25);
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

        $this->assertEquals("Alexandre", $body->firstName);
        $this->assertEquals("Lang", $body->lastName);
        $this->assertNotNull($body->gender);
        $this->assertNotNull($body->firm);
        $this->assertEquals("alexandre.lang@etic.com", $body->email);
        $this->assertNull($body->telephone);
        $this->assertEquals("0033175985495", $body->cellphone);
        $this->assertEquals("Directeur Marketing", $body->position);
        $this->assertEquals("RAS", $body->notes);
        $this->assertEquals(true, $body->old);
    }

    public function testGetContactShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact/664654',
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

        $this->assertSame("lolo", $body->firstName);
        $this->assertSame("momo", $body->lastName);
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

    public function testPutContactWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testDeleteContactShouldReturn204()
    {
        $env = Environment::mock([
        'REQUEST_METHOD' => 'DELETE',
        'REQUEST_URI' => '/api/v1/ua/contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testSearchContactUsingSearchShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact?search=Lan',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(1, $body->content[0]->id);
    }

    public function testGetAllContactsPage0ShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact?pageNumber=0',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(25, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->firm->id));
        $this->assertNotNull(strlen($body->content[0]->gender->id));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->telephone));
        $this->assertNotNull(strlen($body->content[0]->cellphone));
        $this->assertNotNull(strlen($body->content[0]->position));
        $this->assertNotNull(strlen($body->content[0]->notes));
        $this->assertNotNull($body->content[0]->old);
        $this->assertSame(0, $body->meta->page);
        $this->assertSame(2, $body->meta->totalPages);
        $this->assertSame(30, $body->meta->totalItems);
        $this->assertSame(25, $body->meta->itemsPerPage);
    }
    public function testGetAllContactsPage1ShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/contact?pageNumber=1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(5, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->firm->id));
        $this->assertNotNull(strlen($body->content[0]->gender->id));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->telephone));
        $this->assertNotNull(strlen($body->content[0]->cellphone));
        $this->assertNotNull(strlen($body->content[0]->position));
        $this->assertNotNull(strlen($body->content[0]->notes));
        $this->assertNotNull($body->content[0]->old);
        $this->assertSame(1, $body->meta->page);
        $this->assertSame(2, $body->meta->totalPages);
        $this->assertSame(30, $body->meta->totalItems);
        $this->assertSame(25, $body->meta->itemsPerPage);
    }
}