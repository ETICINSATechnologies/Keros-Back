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
            'REQUEST_URI' => '/api/v1/ua/Contact',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(5, count($body->content));
    }
    public function testGetContactShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/Contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals("Marah", $body->getFirstName);
        $this->assertEquals("Tainturier", $body->getLastName);
        $this->assertEquals(1, $body->getGenderId);
        $this->assertEquals(1, $body->getFirmId);
        $this->assertEquals("marah.laurent@gmail.com", $body->getEmail);

        $this->assertEquals("0658984503", $body->getTelephone);
        $this->assertEquals("0175389516", $body->getCellphone);
        $this->assertEquals("chef de projet", $body->getPosition);
        $this->assertEquals("rien a signaler", $body->getNotes);
        $this->assertEquals(true, $body->getOld());
    }
    public function testGetContactShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/Contact/6',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }
    public function testPostContactOnlyWithOnlyRequiredParamsShouldReturn201()
    {
        $post_body = array(
            "firstName"=>"lolo",
            "lastName"=>"momo",
            "genderId"=>1,
            "firmId"=>2,
            "email"=>"lolo.momo@gmail.com",
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/ua/Contact',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame("lolo", $body->firstName);
        $this->assertSame("momo", $body->lasttName);
    }

    public function testPutContactShouldReturn200()
    {

        $post_body = array(
            "firstName"=>"lolo",
            "lastName"=>"momo",
            "genderId"=>1,
            "firmId"=>2,
            "email"=>"lolo.momo@gmail.com",
            "telephone"=>"0675985495",
            "cellphone"=>"0175985495",
            "position"=>"chef de projet",
            "notes"=>"RAS",
            "old"=>true
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/Contact/1',
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
            'REQUEST_URI' => '/api/v1/ua/Contact/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
    }
}