<?php


namespace KerosTest\Sg;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
//use DateTime;
use \Slim\Exception\MethodNotAllowedException;
use \Slim\Exception\NotFoundException;

class MemberInscriptionIntegrationTest extends AppTestCase
{

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetPageMemberInscriptionShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $body = json_decode($response->getBody());
        /*
         $date = new DateTime();
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));
        ($month > 9 && $month < 12) ? (2220 - $year + 1) : (2220 - $year + 2)
            */

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(2, count($body->content));
        $this->assertSame(1, $body->content[0]->id);
        $this->assertSame('Bruce', $body->content[0]->firstName);
        $this->assertSame('Wayne', $body->content[0]->lastName);
        $this->assertSame(1, $body->content[0]->gender->id);
        $this->assertSame('2000-02-14', $body->content[0]->birthday);
        $this->assertSame(3, $body->content[0]->department->id);
        $this->assertSame('bruce.wayne@batman.com', $body->content[0]->email);
        $this->assertSame('0033123456789', $body->content[0]->phoneNumber);
        $this->assertSame(2220, $body->content[0]->outYear);
        $this->assertSame(42, $body->content[0]->nationality->id);
        $this->assertSame(2, $body->content[0]->wantedPole->id);
        $this->assertSame(1, $body->content[0]->address->id);
        $this->assertSame(2, $body->meta->totalItems);
        $this->assertSame(1, $body->meta->totalPages);
        $this->assertSame(0, $body->meta->page);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostMemberInscriptionShouldReturn201()
    {

        $post_body = array(
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'phoneNumber' => '0033454653254',
            'outYear' => 6666,
            'nationalityId' => 133,
            'wantedPoleId' => 5,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $body = json_decode($response->getBody());
        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('Thanos', $body->firstName);
        $this->assertSame('Tueur de monde', $body->lastName);
        $this->assertSame(4, $body->gender->id);
        $this->assertSame('1000-01-01', $body->birthday);
        $this->assertSame(4, $body->department->id);
        $this->assertSame('thanos@claquementdedoigts.com', $body->email);
        $this->assertSame('0033454653254', $body->phoneNumber);
        $this->assertSame(6666, $body->outYear);
        $this->assertSame(133, $body->nationality->id);
        $this->assertSame(5, $body->wantedPole->id);
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetMemberInscriptionShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $body = json_decode($response->getBody());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(1, $body->id);
        $this->assertSame('Bruce', $body->firstName);
        $this->assertSame('Wayne', $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame('2000-02-14', $body->birthday);
        $this->assertSame(3, $body->department->id);
        $this->assertSame('bruce.wayne@batman.com', $body->email);
        $this->assertSame('0033123456789', $body->phoneNumber);
        $this->assertSame(2220, $body->outYear);
        $this->assertSame(42, $body->nationality->id);
        $this->assertSame(2, $body->wantedPole->id);
        $this->assertSame(1, $body->address->id);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetMemberInscriptionShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1000',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testDeleteMemberInscriptionShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1000',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }
}