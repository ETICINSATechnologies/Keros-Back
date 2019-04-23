<?php

namespace KerosTest\Treso;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class FactureIntegrationTest extends AppTestCase
{

    public function testValidateByUaShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/facture/2/validate-ua',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testValidateByPerfShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/facture/2/validate-perf',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testDeleteFactureShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/treso/facture/2',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteFactureShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/treso/facture/5',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testGetAllFactureShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/facture',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $this->assertEquals(4, count($body->content));
        $this->assertEquals(4, $body->meta->totalItems);
        $this->assertSame('23023234', $body->content[0]->numero);
        $this->assertSame(1, $body->content[0]->id);
        $this->assertSame(1, $body->content[0]->documents[0]->id);
        $this->assertSame(false, $body->content[0]->documents[0]->isUploaded);
        $this->assertSame(4, $body->content[0]->documents[3]->id);
        $this->assertSame(true, $body->content[0]->documents[3]->isUploaded);
    }

    public function testGetFactureShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/facture/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame('23023234', $body->numero);
        $this->assertSame(1, $body->id);
        $this->assertSame(1, $body->documents[0]->id);
        $this->assertSame(false, $body->documents[0]->isUploaded);
        $this->assertSame(true, $body->documents[0]->isTemplatable);
        $this->assertSame('Template pro-forma', $body->documents[0]->name);
        $this->assertSame(4, $body->documents[3]->id);
        $this->assertSame(true, $body->documents[3]->isUploaded);
    }

    public function testGetFactureShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/treso/facture/100',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testPostFactureOnlyRequiredFieldShouldReturn201()
    {
        $post_body = array(
            'numero' => "2134",
            'studyId' => 2,
            'type' => 'Acompte',
            'fullAddress' => array(
                'line1' => 'ici c\'est paris',
                'line2' => null,
                'city' => 'Lyon',
                'postalCode' => '12345',
                'countryId' => 44
            ),
            'clientName' => 'Google',
            'contactName' => 'je sais pas',
            'contactEmail' => 'exemple@email.fr',
            'amountDescription' => 'cent quatre euros',
            'subject' => 'fin de la mission',
            'agreementSignDate' => '2019-09-09',
            'amountHT' => 344.45,
            'taxPercentage' => 20.3,
            'dueDate' => '2010-01-01',
            'additionalInformation' => 'test'
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/facture',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame('2134', $body->numero);
        $this->assertSame('ici c\'est paris', $body->fullAddress->line1);
        $this->assertSame('Google', $body->clientName);
        $this->assertSame('je sais pas', $body->contactName);
        $this->assertSame('exemple@email.fr', $body->contactEmail);
        $this->assertSame(2, $body->study->id);
        $this->assertSame('cent quatre euros', $body->amountDescription);
        $this->assertSame('Acompte', $body->type);
        $this->assertSame('fin de la mission', $body->subject);
        $this->assertSame('2019-09-09', $body->agreementSignDate);
        $this->assertSame(344.45, $body->amountHT);
        $this->assertSame(20.3, $body->taxPercentage);
        $this->assertSame('2010-01-01', $body->dueDate);
        $this->assertSame('test', $body->additionalInformation);
        $this->assertSame(date('Y-m-d'), $body->createdDate);
        $this->assertSame(1, $body->createdBy->id);
        $this->assertSame(false, $body->validatedByUa);
        $this->assertSame(null, $body->validatedByUaDate);
        $this->assertSame(null, $body->validatedByUaMember);
        $this->assertSame(false, $body->validatedByPerf);
        $this->assertSame(null, $body->validatedByPerfDate);
        $this->assertSame(null, $body->validatedByPerfMember);
        $this->assertSame(344.45 * ((20.3 / 100) + 1), $body->amountTTC);
        $this->assertSame(false, $body->documents[0]->isUploaded);
        $this->assertSame(false, $body->documents[1]->isUploaded);
        $this->assertSame(false, $body->documents[2]->isUploaded);
        $this->assertSame(false, $body->documents[3]->isUploaded);
    }

    /**
     * @throws \Exception
     */
    public function testPostFactureShouldReturn201()
    {
        $post_body = array(
            'studyId' => 2,
            'type' => 'Acompte',
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/treso/facture',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(201, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame(null, $body->numero);
        $this->assertSame(null, $body->fullAddress);
        $this->assertSame(null, $body->clientName);
        $this->assertSame(null, $body->contactName);
        $this->assertSame(null, $body->contactEmail);
        $this->assertSame(2, $body->study->id);
        $this->assertSame(null, $body->amountDescription);
        $this->assertSame('Acompte', $body->type);
        $this->assertSame(null, $body->subject);
        $this->assertSame(null, $body->agreementSignDate);
        $this->assertSame(null, $body->amountHT);
        $this->assertSame(null, $body->taxPercentage);
        $this->assertSame(null, $body->dueDate);
        $this->assertSame(null, $body->additionalInformation);
        $this->assertSame(date('Y-m-d'), $body->createdDate);
        $this->assertSame(1, $body->createdBy->id);
        $this->assertSame(false, $body->validatedByUa);
        $this->assertSame(null, $body->validatedByUaDate);
        $this->assertSame(null, $body->validatedByUaMember);
        $this->assertSame(false, $body->validatedByPerf);
        $this->assertSame(null, $body->validatedByPerfDate);
        $this->assertSame(null, $body->validatedByPerfMember);
        $this->assertSame(false, $body->documents[0]->isUploaded);
        $this->assertSame(false, $body->documents[1]->isUploaded);
        $this->assertSame(false, $body->documents[2]->isUploaded);
        $this->assertSame(false, $body->documents[3]->isUploaded);
    }

    public function testPutFactureShouldReturn200()
    {
        $post_body = array(
            'numero' => "2134",
            'studyId' => 2,
            'type' => 'Acompte',
            'clientName' => 'Google',
            'contactName' => 'je sais pas',
            'contactEmail' => 'exemple@email.fr',
            'amountDescription' => 'cent quatre euros',
            'subject' => 'fin de la mission',
            'agreementSignDate' => '2019-09-09',
            'amountHT' => 344.45,
            'taxPercentage' => 20.3,
            'dueDate' => '2010-01-01',
            'additionalInformation' => 'test'
        );
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/treso/facture/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertSame('2134', $body->numero);
        $this->assertSame('Google', $body->clientName);
        $this->assertSame('je sais pas', $body->contactName);
        $this->assertSame('exemple@email.fr', $body->contactEmail);
        $this->assertSame(2, $body->study->id);
        $this->assertSame('cent quatre euros', $body->amountDescription);
        $this->assertSame('Acompte', $body->type);
        $this->assertSame('fin de la mission', $body->subject);
        $this->assertSame('2019-09-09', $body->agreementSignDate);
        $this->assertSame(344.45, $body->amountHT);
        $this->assertSame(20.3, $body->taxPercentage);
        $this->assertSame('2010-01-01', $body->dueDate);
        $this->assertSame('test', $body->additionalInformation);
        $this->assertSame(false, $body->documents[0]->isUploaded);
        $this->assertSame(false, $body->documents[1]->isUploaded);
        $this->assertSame(true, $body->documents[2]->isUploaded);
        $this->assertSame(true, $body->documents[3]->isUploaded);
    }

    public function testPutFactureWithEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/treso/facture/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }
}
