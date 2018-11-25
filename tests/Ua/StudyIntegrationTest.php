<?php

namespace KerosTest\Ua;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class StudyIntegrationTest extends AppTestCase
{
    public function testGetAllStudyShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(2, count($body->content));
    }

    public function testGetStudyShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals("12", $body->projectNumber);
        $this->assertEquals("Développement IDE", $body->name);
        $this->assertEquals("Développement d'un IDE pour utilisation interne", $body->description);
        $this->assertEquals("1", $body->field->id);
        $this->assertEquals("Web", $body->field->label);
        $this->assertEquals("2", $body->status->id);
        $this->assertEquals("En clôture", $body->status->label);
        $this->assertEquals("1", $body->provenance->id);
        $this->assertEquals("Site Web", $body->provenance->label);
        $this->assertEquals("2018-11-10", $body->signDate);
        $this->assertEquals("1", $body->firm->id);
    }

    public function testGetStudyShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/100',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostStudyOnlyWithOnlyRequiredParamsShouldReturn201()
    {

        $post_body = array(
            "id" =>2,
            "projectNumber"=>13,
            "name"=>"Facebook",
            "description"=>"C est le feu",
            "fieldId"=>1,
            "provenanceId"=>1,
            "statusId"=>1,
            "firmId"=>1,
            "contactIds"=>array(),
            "leaderIds"=>array(),
            "consultantIds"=>array(),
            "qualityManagerIds"=>array(),

        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/ua/study',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertSame(3, $body->id);
        $this->assertSame("Facebook", $body->name);
    }

    public function testPutStudyShouldReturn200()
    {

        $post_body = array(
            "projectNumber"=>13,
            "name"=>"Twitter",
            "description"=>"C est le feu",
            "fieldId"=>1,
            "provenanceId"=>1,
            "statusId"=>1,
            "firmId"=>1,
            "contactIds"=>array(),
            "leaderIds"=>array(),
            "consultantIds"=>array(),
            "qualityManagerIds"=>array(),
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertSame("Twitter", $body->name);
    }

    public function testPutStudyWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }
}