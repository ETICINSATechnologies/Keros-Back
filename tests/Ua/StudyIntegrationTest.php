<?php

namespace KerosTest\Ua;

use Keros\Tools\Validator;
use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class StudyIntegrationTest extends AppTestCase
{
    public function testGetCurrentUserStudiesShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $bodies = json_decode($response->getBody());

        $bodies = Validator::requiredArray($bodies);
        foreach ($bodies as $body) {
            $this->assertEquals("2", $body->id);
            $this->assertEquals("Tests d'acidité dans le Rhône", $body->name);
            $this->assertEquals("Créateur de IDE", $body->description);
            $this->assertEquals("1", $body->field->id);
            $this->assertEquals("Web", $body->field->label);
            $this->assertEquals("2", $body->status->id);
            $this->assertEquals("En clôture", $body->status->label);
            $this->assertEquals("1", $body->provenance->id);
            $this->assertEquals("Site Web", $body->provenance->label);
            $this->assertEquals("2018-11-10", $body->signDate);
            $this->assertEquals("2", $body->firm->id);
        }
    }

    public function testGetAllDocumentsShouldReturn200(){
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/2/documents',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(2, count($body->documents));
        $this->assertSame(1, $body->documents[0]->id);
        $this->assertSame('testGet', $body->documents[0]->name);
        $this->assertSame('http://keros-api-dev.etic-insa.com/api/v1/ua/study/2/template/1', $body->documents[0]->generateLocation);
    }

    public function testGetAllDocumentsShouldReturn500(){
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/1/documents',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(500, $response->getStatusCode());
    }

    public function testDeleteStudyShouldReturn204 ()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/ua/study/2',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteStudyShouldReturn404 ()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/ua/study/5',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

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
            "id" =>3,
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
            "confidential"=>true,
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