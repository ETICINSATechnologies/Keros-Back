<?php

namespace KerosTest\Ua;

use Keros\Tools\ConfigLoader;
use Keros\Tools\Validator;
use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class StudyIntegrationTest extends AppTestCase
{

    public function testDeleteAllStudyShouldReturn204()
    {
        for($id = 1;  $id <= 3; $id++) {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'DELETE',
                'REQUEST_URI' => "/api/v1/ua/study/$id",
            ]);
            $req = Request::createFromEnvironment($env);
            $this->app->getContainer()['request'] = $req;
            $response = $this->app->run(false);
            $this->assertSame(204, $response->getStatusCode());
        }
    }


    public function testGetStudyWithMainLeaderManagerConsultant()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

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
        $this->assertEquals("4", $body->leaders[0]->id);
        $this->assertEquals("3", $body->leaders[1]->id);
        $this->assertEquals("4", $body->qualityManagers[0]->id);
        $this->assertEquals("3", $body->qualityManagers[1]->id);
        $this->assertEquals("2", $body->consultants[0]->id);
        $this->assertNotNull($body->documents);
        $this->assertEquals(3, sizeof($body->documents));

    }

    public function testGetCurrentUserConsultantStudiesShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 5);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $bodies = json_decode($response->getBody());

        $bodies = Validator::requiredArray($bodies);
        foreach ($bodies as $body) {
            $this->assertEquals("1", $body->id);
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
            $this->assertNotNull($body->documents);
        }
    }

    public function testGetCurrentUserMemberStudiesShouldReturn200()
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
            $this->assertNotNull($body->documents);
        }
    }

    public function testDeleteStudyShouldReturn204()
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

    public function testDeleteStudyShouldReturn404()
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

    public function testGetAllStudyWithoutBeingLeaderShouldReturn200()
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

    public function testGetAllStudyWithLeaderShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 3);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(3, count($body->content));
        foreach($body->content as $study)
            $this->assertNotNull($study->documents);
    }

    public function testGetAllStudyWithRightShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 6);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(3, count($body->content));
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
        $this->assertNotNull($body->documents);
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

    public function testGetStudyConfidentialWithoutRightShouldReturn401()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/3',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 9);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testGetConfidentialStudyWithEnoughRightShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/3',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(3, $body->id);
        $this->assertNotNull($body->documents);
    }

    public function testGetConfidentialStudyWithLeaderMemberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/3',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 3);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(3, $body->id);
        $this->assertNotNull($body->documents);
    }

    public function testPostStudyOnlyWithOnlyRequiredParamsShouldReturn201()
    {

        $post_body = array(
            "id" =>4,
            "name"=>"Facebook",

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
        $this->assertSame(4, $body->id);
        $this->assertSame("Facebook", $body->name);
        $this->assertNotNull($body->documents);
    }

    public function testPostStudyShouldReturn201()
    {
        $post_body = array(
            "name"=>"Twitter",
            "description"=>"C est le feu",
            "provenanceId"=>1,
            "statusId"=>1,
            "firmId"=>1,
            "contactIds"=>array(),
            "leaderIds"=>array(),
            "consultantIds"=>array(),
            "qualityManagerIds"=>array(),
            "confidential"=>true,
            "mainLeader"=>null,
            "mainQualityManager"=>null,
            "mainConsultant"=>null
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
        $this->assertSame(4, $body->id);
        $this->assertSame("Twitter", $body->name);
        $this->assertSame("C est le feu", $body->description);
        $this->assertNotNull($body->documents);

    }

    public function testPutStudyShouldReturn200()
    {
        $method = 'PUT';
        $post_body = array(
            "name" => "Twitter",
            "description" => "C est le feu",
            "fieldId" => 1,
            "provenanceId" => 1,
            "statusId" => 1,
            "firmId" => 1,
            "contactIds" => array(),
            "leaderIds" => array(),
            "consultantIds" => array(),
            "qualityManagerIds" => array(),
            "confidential" => true,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withAttribute("userId",6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertNotNull($body->documents);
        $this->assertSame("Twitter", $body->name);
    }

    public function testPutStudyWithoutRightShouldReturn401()
    {
        $method = 'PUT';
        $post_body = array(
            "name" => "Twitter",
            "description" => "C est le feu",
            "fieldId" => 1,
            "provenanceId" => 1,
            "statusId" => 1,
            "firmId" => 1,
            "contactIds" => array(),
            "leaderIds" => array(),
            "consultantIds" => array(),
            "qualityManagerIds" => array(),
            "confidential" => true,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withAttribute("userId", 1);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPutStudyUpdateQualityManagerWithoutRightShouldReturn401()
    {
        $method = 'PUT';
        $post_body = array(
            "name" => "Twitter",
            "description" => "C est le feu",
            "fieldId" => 1,
            "provenanceId" => 1,
            "statusId" => 1,
            "firmId" => 1,
            "contactIds" => array(),
            "leaderIds" => array(),
            "consultantIds" => array(),
            "qualityManagerIds" => array(1),
            "confidential" => true,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withAttribute("userId", 1);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPutStudyUpdateQualityManagerWithRightShouldReturn200()
    {
        $method = 'PUT';
        $post_body = array(
            "name" => "Twitter",
            "description" => "C est le feu",
            "fieldId" => 1,
            "provenanceId" => 1,
            "statusId" => 1,
            "firmId" => 1,
            "contactIds" => array(),
            "leaderIds" => array(),
            "consultantIds" => array(),
            "qualityManagerIds" => array(1),
            "confidential" => true,
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withAttribute("userId", 6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testPutStudyWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId",6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testPutStudyWithOnlyRequiredParamsShouldReturn200()
    {

        $post_body = array(
            "name"=>"Twitter",
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/study/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId",6);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertNotNull($body->documents);
        $this->assertSame("Twitter", $body->name);
    }
}