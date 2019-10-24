<?php

namespace KerosTest\Sg;

use Keros\Tools\ConfigLoader;
use KerosTest\AppTestCase;
use mikehaertl\pdftk\Pdf;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\UploadedFile;

class MemberInscriptionDocumentIntegrationTest extends AppTestCase
{

    public function testGetGeneratedDocumentShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/2/document/1/generate',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $body = json_decode($response->getBody());

        $this->assertSame(200, $response->getStatusCode());


        $location = strstr($body->location, "/generated/");
        $location = str_replace("/generated/", "documents/tmp/", $location);
        $pdf1 = new Pdf("tests/DocumentsTests/memberInscription2.pdf");
        $pdf2 = new Pdf($location);
        $this->assertEquals($pdf1->getDataFields(), $pdf2->getDataFields());
    }

    public function testGetGeneratedDocumentWithAccentShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/3/document/1/generate',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $body = json_decode($response->getBody());

        $this->assertSame(200, $response->getStatusCode());


        $location = strstr($body->location, "/generated/");
        $location = str_replace("/generated/", "documents/tmp/", $location);
        $pdf1 = new Pdf("tests/DocumentsTests/memberInscription3.pdf");
        $pdf2 = new Pdf($location);
        $this->assertEquals($pdf1->getDataFields(), $pdf2->getDataFields());
    }

    public function testPostDocumentShouldReturn200()
    {
        $handle = fopen("test.txt", "w");
        $file = new UploadedFile('test.txt', 'test.txt', 'text/plain', filesize('test.txt'));
        fclose($handle);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/2/document/1',
            'slim.files' => ['file' => $file],
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        //on test qu'il est maintenant bien retourné
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/2/document/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $kerosConfig = ConfigLoader::getConfig();
        $this->assertSame($kerosConfig["BACK_URL"] . DIRECTORY_SEPARATOR . '/generated/', pathinfo($body->location, PATHINFO_DIRNAME) . '/');
        $this->assertSame('txt', pathinfo($body->location, PATHINFO_EXTENSION));
    }
}