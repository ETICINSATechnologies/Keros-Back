<?php
namespace KerosTest\Ua;
use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
class FirmIntegrationTest extends AppTestCase
{
    public function testGetAllFirmShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/firm',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(2, count($body->content));
    }
    public function testGetFirmShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/firm/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame("Google", $body->name);
    }
    public function testGetFirmShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/firm/3',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }
    public function testPostFirmShouldReturn201()
    {
        $address=array(
            "line1"=>"20 avenue albert Eistein",
            "line2"=>"residence g",
            "city"=>"lyon",
            "postalCode"=>69100,
            "countryId"=>1
        );
        $post_body = array(
            "siret" => "013456789",
            "name" => "Google company",
            "address" => $address,
            "typeId" => 5,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/ua/firm',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame("013456789", $body->siret);
        $this->assertSame("Google company", $body->name);
    }
    public function testPutFirmShouldReturn200()
    {
        $address=array(
            "line1"=>"20 avenue albert Eistein",
            "line2"=>"residence g",
            "city"=>"lyon",
            "postalCode"=>69100,
            "countryId"=>1
        );
        $post_body = array(
            "siret" => "013456789",
            "name" => "Google company",
            "address" => $address,
            "typeId" => 5,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/firm/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame("Google company", $body->name);
    }
    public function testPutFirmWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/firm/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(400, $response->getStatusCode());
    }
}