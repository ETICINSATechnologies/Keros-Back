<?php

namespace KerosTest\Cat;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

class CatIntegrationTest extends AppTestCase
{
    public function testGetAllCatsShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/cat',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 200);

        $body = json_decode($response->getBody());
        $this->assertEquals(3, count($body->content));
        $this->assertNotNull(count($body->content[0]->id));
        $this->assertNotNull(count($body->content[0]->name));
        $this->assertNotNull(count($body->content[0]->height));

        // Pas besoin de tester la pagination pour vos autres tests (on fera à part)
        $this->assertEquals(0, $body->meta->page);
        $this->assertEquals(1, $body->meta->totalPages);
        $this->assertEquals(3, $body->meta->totalItems);
        $this->assertEquals(25, $body->meta->itemsPerPage);
    }
}