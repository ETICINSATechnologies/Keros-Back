<?php

namespace KerosTest\Ua;


use Keros\Tools\ConfigLoader;
use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\UploadedFile;

class StudyDocumentIntegrationTest extends AppTestCase
{
    public function testGetDocumentShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/1/document/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $kerosConfig = ConfigLoader::getConfig();
        $this->assertSame($kerosConfig["BACK_URL"] . DIRECTORY_SEPARATOR . '/generated/', pathinfo($body->location, PATHINFO_DIRNAME) . '/');
    }

    public function testPostDocumentShouldReturn200()
    {
        $handle = fopen("test.txt", "w");
        $file = new UploadedFile('test.txt', 'test.txt', 'text/plain', filesize('test.txt'));

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/ua/study/1/document/2',
            'slim.files' => ['file' => $file],
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        fclose($handle);
        $this->assertSame(200, $response->getStatusCode());

        //on test qu'il est maintenant bien retourné
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/1/document/2',
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

    public function testGetDocumentShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/ua/study/2/document/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(400, $response->getStatusCode());
    }

}