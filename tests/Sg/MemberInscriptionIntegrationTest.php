<?php


namespace KerosTest\Sg;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
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
        $this->assertSame(2021, $body->content[0]->outYear);
        $this->assertSame(42, $body->content[0]->nationality->id);
        $this->assertSame(2, $body->content[0]->wantedPole->id);
        $this->assertSame(1, $body->content[0]->address->id);
        $this->assertSame(false, $body->content[0]->hasPaid);
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
            'hasPaid' => true,
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
        $this->assertSame(true, $body->hasPaid);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostMemberInscriptionOnlyRequiredFieldsShouldReturn201()
    {

        $post_body = array(
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'nationalityId' => 133,
            'wantedPoleId' => 5,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'hasPaid' => true,
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
        $this->assertSame(null, $body->phoneNumber);
        $this->assertSame(null, $body->outYear);
        $this->assertSame(133, $body->nationality->id);
        $this->assertSame(5, $body->wantedPole->id);
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->hasPaid);
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
        $this->assertSame(2021, $body->outYear);
        $this->assertSame(42, $body->nationality->id);
        $this->assertSame(2, $body->wantedPole->id);
        $this->assertSame(1, $body->address->id);
        $this->assertSame(false, $body->hasPaid);
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

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testDeleteMemberInscriptionShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testValidateMemberInscriptionShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1/validate',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());

        //On vérifie maintenant que le membre a bien été ajouté.
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/29', //On get le dernier membre (max de kerosData.sql + 1)
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $body = json_decode($response->getBody());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotNull($body->id);
        $this->assertSame('Bruce.Wayne', $body->username);
        $this->assertSame("Bruce", $body->firstName);
        $this->assertSame("Wayne", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("bruce.wayne@batman.com", $body->email);
        $this->assertSame("2000-02-14", $body->birthday);
        $this->assertSame(3, $body->department->id);
        //Ce test rate au changement d'année scolaire (normalement mdr). Il faudra donc augmenter les outYear dans kerosData.sql de 1.
        $this->assertSame(3, $body->schoolYear);
        $this->assertSame("0033123456789", $body->telephone);
        $this->assertSame(null, $body->company);
        $this->assertSame(null, $body->profilePicture);
        $this->assertNotNull($body->address->id);
        $this->assertSame('13 Rue du renard', $body->address->line1);
        $this->assertSame(0, count($body->positions));
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testValidateMemberInscriptionShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1000/validate',
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
    public function testDoubleValidateMemberInscriptionShouldReturn204and404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1/validate',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1/validate',
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
    public function testPutMemberInscriptionShouldReturn200()
    {

        $put_body = array(
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
            'hasPaid' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $body = json_decode($response->getBody());
        $this->assertSame(200, $response->getStatusCode());
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
        $this->assertSame(true, $body->hasPaid);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPutMemberInscriptionShouldReturn404()
    {

        $put_body = array(
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
            'hasPaid' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/membre-inscription/1000',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }
}