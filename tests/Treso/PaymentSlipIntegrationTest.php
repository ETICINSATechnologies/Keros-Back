<?php

namespace KerosTest\Treso;

use Keros\Tools\ConfigLoader;
use Keros\Tools\Validator;
use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class PaymentSlipIntegrationTest extends AppTestCase
{
    public function testGetAllPaymentSlipShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/payment-slip',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(1, count($body->content));
    }

    public function testGetPaymentSlipShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals("102383203", $body->missionRecapNumber);
        $this->assertEquals("Shrek", $body->consultantName);
        $this->assertEquals("12320183", $body->consultantSocialSecurityNumber);
        $this->assertEquals(7, $body->address->id);
        $this->assertEquals("shrek@fortfortlointain.fr", $body->email);
        $this->assertEquals(1, $body->study->id);
        $this->assertEquals("L'âne", $body->clientName);
        $this->assertEquals("Le chat Potté", $body->projectLead);
        $this->assertEquals(1, $body->consultant->consultantId);
        $this->assertEquals(false, $body->isTotalJeh);
        $this->assertEquals(false, $body->isStudyPaid);
        $this->assertEquals("Facture payée", $body->amountDescription);
        $this->assertEquals("2022-05-15 00:00:00.000000", $body->createdDate->date);
        $this->assertEquals(1, $body->createdBy->id);
        $this->assertEquals(false, $body->validatedByUa);
        $this->assertEquals("2022-05-15 00:00:00.000000", $body->validatedByUaDate->date);
        $this->assertEquals(2, $body->validatedByUaMember->id);
        $this->assertEquals(false, $body->validatedByPerf);
        $this->assertEquals("2022-05-15 00:00:00.000000", $body->validatedByPerfDate->date);
        $this->assertEquals(3, $body->validatedByPerfMember->id);
    }

    public function testGetPaymentSlipShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/100',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDeleteStudyShouldReturn204 ()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/2',
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
            'REQUEST_URI' => '/api/v1/treso/payment-slip/200000',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostStudyOnlyRequiredFieldsShouldReturn201(){

        $post_body = array(
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/payment-slip',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame(3, $body->id);
    }
}