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
        $this->assertEquals(false, $body->isTotalJeh);
        $this->assertEquals(false, $body->isStudyPaid);
        $this->assertEquals("Facture payée", $body->amountDescription);
        $this->assertEquals("2022-05-15", $body->createdDate);
        $this->assertEquals(1, $body->createdBy->id);
        $this->assertEquals(false, $body->validatedByUa);
        $this->assertEquals(null, $body->validatedByUaDate);
        $this->assertEquals(null, $body->validatedByUaMember);
        $this->assertEquals(false, $body->validatedByPerf);
        $this->assertEquals(null, $body->validatedByPerfDate);
        $this->assertEquals(null, $body->validatedByPerfMember);
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

    public function testDeleteStudyShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1',
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
            'REQUEST_URI' => '/api/v1/treso/payment-slip/200000',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostStudyOnlyRequiredFieldsShouldReturn201()
    {

        $post_body = array(
            "studyId" => 2
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
        $this->assertSame(2, $body->id);
        $this->assertSame(null, $body->missionRecapNumber);
        $this->assertSame(null, $body->consultantName);
        $this->assertSame(null, $body->consultantSocialSecurityNumber);
        $this->assertSame(null, $body->address);
        $this->assertSame(null, $body->email);
        $this->assertSame(2, $body->study->id);
        $this->assertSame(null, $body->clientName);
        $this->assertSame(null, $body->projectLead);
        $this->assertSame(false, $body->isTotalJeh);
        $this->assertSame(false, $body->isStudyPaid);
        $this->assertSame(null, $body->amountDescription);
        $this->assertSame(1, $body->createdBy->id);
        $this->assertSame(false, $body->validatedByUa);
        $this->assertSame(null, $body->validatedByUaDate);
        $this->assertSame(null, $body->validatedByUaMember);
        $this->assertSame(false, $body->validatedByPerf);
        $this->assertSame(null, $body->validatedByPerfDate);
        $this->assertSame(null, $body->validatedByPerfMember);
    }

    public function testPostStudyShouldReturn201()
    {
        $post_body = array(
            "missionRecapNumber" => "string",
            "consultantName" => "LOREM Ipsum-Nawa",
            "consultantSocialSecurityNumber" => "string",
            "address" => array(
                "line1" => "13 Rue du Renard",
                "line2" => "Appt 402",
                "city" => "Villeurbanne",
                "postalCode" => 69100,
                "countryId" => 1
            ),
            "email" => "string@osef.fr",
            "studyId" => 2,
            "clientName" => "string",
            "projectLead" => "LOREM Ipsum-Nawa2",
            "isTotalJeh" => true,
            "isStudyPaid" => true,
            "amountDescription" => "D’un montant de quatre mille quatre-vingt euros toutes taxes comprises (4080€ TTC), correspondant à la réalisation de 13 JEH"
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
        $this->assertSame(2, $body->id);
        $this->assertSame("string", $body->missionRecapNumber);
        $this->assertSame("LOREM Ipsum-Nawa", $body->consultantName);
        $this->assertSame("string", $body->consultantSocialSecurityNumber);
        $this->assertSame("13 Rue du Renard", $body->address->line1);
        $this->assertSame("string@osef.fr", $body->email);
        $this->assertSame(2, $body->study->id);
        $this->assertSame("string", $body->clientName);
        $this->assertSame("LOREM Ipsum-Nawa2", $body->projectLead);
        $this->assertSame(true, $body->isTotalJeh);
        $this->assertSame(true, $body->isStudyPaid);
        $this->assertSame("D’un montant de quatre mille quatre-vingt euros toutes taxes comprises (4080€ TTC), correspondant à la réalisation de 13 JEH", $body->amountDescription);
        $this->assertSame(1, $body->createdBy->id);
        $this->assertSame(false, $body->validatedByUa);
        $this->assertSame(null, $body->validatedByUaDate);
        $this->assertSame(null, $body->validatedByUaMember);
        $this->assertSame(false, $body->validatedByPerf);
        $this->assertSame(null, $body->validatedByPerfDate);
        $this->assertSame(null, $body->validatedByPerfMember);
    }

    public function testPutStudyShouldReturn201()
    {
        $post_body = array(
            "missionRecapNumber" => "string",
            "consultantName" => "LOREM Ipsum-Nawa",
            "consultantSocialSecurityNumber" => "string",
            "address" => array(
                "line1" => "13 Rue du Renard",
                "line2" => "Appt 402",
                "city" => "Villeurbanne",
                "postalCode" => 69100,
                "countryId" => 1
            ),
            "email" => "string@eticlesbest.cop",
            "studyId" => 2,
            "clientName" => "string",
            "projectLead" => "LOREM Ipsum-Nawa2",
            "isTotalJeh" => true,
            "isStudyPaid" => true,
            "amountDescription" => "D’un montant de quatre mille quatre-vingt euros toutes taxes comprises (4080€ TTC), correspondant à la réalisation de 13 JEH"
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame("string", $body->missionRecapNumber);
        $this->assertSame("LOREM Ipsum-Nawa", $body->consultantName);
        $this->assertSame("string", $body->consultantSocialSecurityNumber);
        $this->assertSame("13 Rue du Renard", $body->address->line1);
        $this->assertSame("string@eticlesbest.cop", $body->email);
        $this->assertSame(2, $body->study->id);
        $this->assertSame("string", $body->clientName);
        $this->assertSame("LOREM Ipsum-Nawa2", $body->projectLead);
        $this->assertSame(true, $body->isTotalJeh);
        $this->assertSame(true, $body->isStudyPaid);
        $this->assertSame("D’un montant de quatre mille quatre-vingt euros toutes taxes comprises (4080€ TTC), correspondant à la réalisation de 13 JEH", $body->amountDescription);
        $this->assertSame(1, $body->createdBy->id);
        $this->assertEquals(false, $body->validatedByUa);
        $this->assertEquals(null, $body->validatedByUaDate);
        $this->assertEquals(null, $body->validatedByUaMember);
        $this->assertEquals(false, $body->validatedByPerf);
        $this->assertEquals(null, $body->validatedByPerfDate);
        $this->assertEquals(null, $body->validatedByPerfMember);
    }

    public function testPutStudyWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testPutStudyWithOnlyRequiredParamsShouldReturn200()
    {
        $post_body = array(
            "studyId" => 2
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals("102383203", $body->missionRecapNumber);
        $this->assertEquals("Shrek", $body->consultantName);
        $this->assertEquals("12320183", $body->consultantSocialSecurityNumber);
        $this->assertEquals(7, $body->address->id);
        $this->assertEquals("shrek@fortfortlointain.fr", $body->email);
        $this->assertEquals(2, $body->study->id);
        $this->assertEquals("L'âne", $body->clientName);
        $this->assertEquals("Le chat Potté", $body->projectLead);
        $this->assertEquals(false, $body->isTotalJeh);
        $this->assertEquals(false, $body->isStudyPaid);
        $this->assertEquals("Facture payée", $body->amountDescription);
        $this->assertEquals("2022-05-15", $body->createdDate);
        $this->assertEquals(1, $body->createdBy->id);
        $this->assertEquals(false, $body->validatedByUa);
        $this->assertEquals(null, $body->validatedByUaDate);
        $this->assertEquals(null, $body->validatedByUaMember);
        $this->assertEquals(false, $body->validatedByPerf);
        $this->assertEquals(null, $body->validatedByPerfDate);
        $this->assertEquals(null, $body->validatedByPerfMember);
    }

    public function testValidateByUaShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1/validate-ua',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

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
        $this->assertEquals(false, $body->isTotalJeh);
        $this->assertEquals(false, $body->isStudyPaid);
        $this->assertEquals("Facture payée", $body->amountDescription);
        $this->assertEquals("2022-05-15", $body->createdDate);
        $this->assertEquals(1, $body->createdBy->id);
        $this->assertEquals(true, $body->validatedByUa);
        $this->assertNotNull($body->validatedByUaDate);
        $this->assertEquals(1, $body->validatedByUaMember->id);
        $this->assertEquals(false, $body->validatedByPerf);
        $this->assertEquals(null, $body->validatedByPerfDate);
        $this->assertEquals(null, $body->validatedByPerfMember);
    }

    public function testValidateByPerfShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/payment-slip/1/validate-perf',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

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
        $this->assertEquals(false, $body->isTotalJeh);
        $this->assertEquals(false, $body->isStudyPaid);
        $this->assertEquals("Facture payée", $body->amountDescription);
        $this->assertEquals("2022-05-15", $body->createdDate);
        $this->assertEquals(1, $body->createdBy->id);
        $this->assertEquals(false, $body->validatedByUa);
        $this->assertEquals(null, $body->validatedByUaDate);
        $this->assertEquals(null, $body->validatedByUaMember);
        $this->assertEquals(true, $body->validatedByPerf);
        $this->assertNotNull($body->validatedByPerfDate);
        $this->assertEquals(1, $body->validatedByPerfMember->id);
    }
}