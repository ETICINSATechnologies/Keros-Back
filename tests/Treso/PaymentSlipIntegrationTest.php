<?php

namespace KerosTest\Treso;

use Keros\Tools\ConfigLoader;
use Keros\Tools\Validator;
use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class PaymentSlipIntegrationTest extends AppTestCase
{

    public function testGetAllDocumentsShouldReturn200(){
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/2/documents',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $kerosConfig = ConfigLoader::getConfig();

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(3, count($body->documents));
        $this->assertSame(1, $body->documents[0]->id);
        $this->assertSame('testGet', $body->documents[0]->name);
        $this->assertSame($kerosConfig['BACK_URL'] . '/api/v1/ua/paymentSlip/2/template/1', $body->documents[0]->generateLocation);
    }

    public function testGetAllDocumentsShouldReturn400(){
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/1/documents',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testDeletePaymentSlipShouldReturn204 ()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/2',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeletePaymentSlipShouldReturn404 ()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/5',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetAllPaymentSlipShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(2, count($body->content));
    }

    public function testGetPaymentSlipShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/1',
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

    public function testGetPaymentSlipShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/100',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostPaymentSlipOnlyWithOnlyRequiredParamsShouldReturn201()
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
            'REQUEST_URI' => '/api/v1/ua/paymentSlip',
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

    public function testPutPaymentSlipShouldReturn200()
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
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertSame("Twitter", $body->name);
    }

    public function testPutPaymentSlipWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/ua/paymentSlip/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }
}