<?php

namespace KerosTest\Member;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\UploadedFile;

class MemberIntegrationTest extends AppTestCase
{
    public function testGetAllMembersPage0ShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member?pageNumber=0',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(25, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->username));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->gender->id));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->birthday));
        $this->assertNotNull(strlen($body->content[0]->address->id));
        $this->assertSame(26, $body->meta->totalItems);
    }

    public function testGetAllMembersPage1ShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member?pageNumber=1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(1, count($body->content));
        $this->assertSame(26, $body->meta->totalItems);
    }

    public function testLikeSearchMemberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member?firstName=Lauren&positionId=1&year=2018',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(3, $body->content[0]->id);
        $this->assertSame(1, $body->content[0]->positions[0]->id);
        $this->assertSame(2, $body->content[0]->positions[1]->id);
        $this->assertSame(3, $body->content[0]->positions[2]->id);
    }

    public function testSearchMemberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member?firstName=Laurence&positionId=1&year=2018',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(3, $body->content[0]->id);
        $this->assertSame(1, $body->content[0]->positions[0]->id);
        $this->assertSame(2, $body->content[0]->positions[1]->id);
        $this->assertSame(3, $body->content[0]->positions[2]->id);
    }

    public function testSearchLatestMemberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member?year=latest',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(3, sizeof($body->content));
        $this->assertSame(1, $body->content[0]->id);
        $this->assertSame(3, $body->content[0]->positions[0]->id);
        $this->assertSame(3, $body->content[1]->id);
        $this->assertSame(1, $body->content[1]->positions[0]->id);
        $this->assertSame(2, $body->content[1]->positions[1]->id);
        $this->assertSame(3, $body->content[1]->positions[2]->id);
    }

    public function testGetConnectedMemberEmptyBodyShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;

        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body);
        $this->assertSame(1, $body->id);
    }

    public function testPutConnectedMemberEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testPutConnectedMemberShouldReturn200()
    {
        $put_body = array(
            "username" => "newusername",
            "password" => "password",
            "firstName" => "firstName",
            "lastName" => "lastName",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "address" => [
                "line1" => "20 avenue albert Einstein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "schoolYear" => 1,
            "departmentId" => 1,
            "positions" => [
                array(
                    "id" => 3,
                    "year" => 2018,
                    "isBoard" => true
                ),
                array(
                    "id" => 4,
                    "year" => 2019,
                    "isBoard" => false
                )
            ],
            "company" => "Amazon",
            "droitImage" => true
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("firstName", $body->firstName);
        $this->assertSame("lastName", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertNotNull($body->address->id);
        $this->assertSame(3, $body->positions[0]->id);
        $this->assertSame(4, $body->positions[1]->id);
        $this->assertSame(true, $body->droitImage);
    }

    public function testDeleteMembersShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteInvalidMemberShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/member/100000',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());

    }

    public function testGetAllMembersShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertEquals(25, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->username));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->gender->id));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->birthday));
        $this->assertNotNull(strlen($body->content[0]->address->id));
    }

    public function testGetMemberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(1, $body->id);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("Conor", $body->firstName);
        $this->assertSame("Breeze", $body->lastName);
        $this->assertSame("1975-12-25", $body->birthday);
        $this->assertSame("+332541254", $body->telephone);
        $this->assertSame("fake.mail@fake.com", $body->email);
        $this->assertSame(2, $body->address->id);
        $this->assertSame(true, $body->droitImage);
    }

    public function testGetMemberShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/10090909',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostMemberShouldReturn200()
    {
        $post_body = array(
            "username" => "newusername",
            "password" => "password",
            "firstName" => "firstname",
            "lastName" => "lastname",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "departmentId" => 1,
            "schoolYear" => 1,
            "address" => [
                "line1" => "20 avenue albert Eistein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "disabled" => null,
            "positions" => [
                array(
                    "id" => 3,
                    "year" => 2018,
                    "isBoard" => true
                ),
                array(
                    "id" => 4,
                    "year" => 2019,
                    "isBoard" => false
                )
            ],
            "company" => "Amazon",
            "droitImage" => false
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withAttribute("userId", 6);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(201, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("lastname", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertSame(3, $body->positions[0]->id);
        $this->assertSame(4, $body->positions[1]->id);
        $this->assertSame(false, $body->droitImage);
    }

    public function testPutMemberShouldReturn200()
    {
        $post_body = array(
            "username" => "newusername",
            "password" => "password",
            "firstName" => "firstName",
            "lastName" => "lastName",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "address" => [
                "line1" => "20 avenue albert Einstein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "schoolYear" => 1,
            "departmentId" => 1,
            "positions" => [
                array(
                    "id" => 3,
                    "year" => 2018,
                    "isBoard" => true
                ),
                array(
                    "id" => 4,
                    "year" => 2019,
                    "isBoard" => false
                )
            ],
            "company" => "Amazon",
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("firstName", $body->firstName);
        $this->assertSame("lastName", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertNotNull($body->address->id);
        $this->assertSame(3, $body->positions[0]->id);
        $this->assertSame(4, $body->positions[1]->id);
        $this->assertSame(true, $body->droitImage);
    }

    public function testPutMemberEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/member/1',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testPostMemberPhotoShouldReturn204()
    {
        $temp_fileName = "tempPhoto.jpg";
        $handle = fopen($temp_fileName, 'w') or die('Cannot open file:  '.$temp_fileName);
        $file = new UploadedFile($temp_fileName, $temp_fileName, 'image/jpeg', filesize($temp_fileName));

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member/1/photo',
            'slim.files' => ['file' => $file],
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        fclose($handle);
        $response = $this->app->run(false);
        $this->assertSame(204, $response->getStatusCode());
    }

    public function testPostMemberPhotoShouldReturn400()
    {
        $temp_fileName = "tempFile.csv";
        $handle = fopen($temp_fileName, 'w') or die('Cannot open file:  '.$temp_fileName);
        $file = new UploadedFile($temp_fileName, $temp_fileName, 'text/csv', filesize($temp_fileName));

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member/1/photo',
            'slim.files' => ['file' => $file],
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        fclose($handle);
        $response = $this->app->run(false);
        if (file_exists($temp_fileName)) {
            unlink($temp_fileName);
        }
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testGetMemberPhotoShouldReturn200()
    {
        $fileName = "tempPhoto.jpg";
        $handle = fopen($fileName, "w");
        $file = new UploadedFile($fileName, $fileName, 'image/jpeg', filesize($fileName));

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member/1/photo',
            'slim.files' => ['file' => $file],
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/1/photo',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        fclose($handle);
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetMemberPhotoShouldReturn404()
    {   
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member/2/photo',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testDeleteMemberPhotoShouldReturn204()
    {   $fileName = "tempPhoto.jpg";
        $handle = fopen($fileName, "w");
        fclose($handle);
        $file = new UploadedFile($fileName, $fileName, 'image/jpeg', filesize($fileName));

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member/1/photo',
            'slim.files' => ['file' => $file],
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/member/1/photo',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteMemberPhotoShouldReturn404()
    {   
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/member/2/photo',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }
    
    public function testPostMemberWithoutRightShouldReturn401()
    {
        $post_body = array(
            "username" => "newusername",
            "password" => "password",
            "firstName" => "firstname",
            "lastName" => "lastname",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "departmentId" => 1,
            "schoolYear" => 1,
            "address" => [
                "line1" => "20 avenue albert Eistein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "disabled" => null,
            "positions" => [
                array(
                    "id" => 3,
                    "year" => 2018,
                    "isBoard" => true
                ),
                array(
                    "id" => 4,
                    "year" => 2019,
                    "isBoard" => false
                )
            ],
            "company" => "Amazon",
            "profilePicture" => "http://image.png"
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/member',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testSearchMemberUsingSearchShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/member?search=Laur',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(3, $body->content[0]->id);
    }


    public function testDeleteAllExistingMemberShouldReturn204 (){
        do {
            $env = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/api/v1/core/member',
            ]);
            $req = Request::createFromEnvironment($env);
            $this->app->getContainer()['request'] = $req;
            $response = $this->app->run(false);
            $body = json_decode($response->getBody());

            foreach ($body->content as $member){
                $env = Environment::mock([
                    'REQUEST_METHOD' => 'DELETE',
                    'REQUEST_URI' => '/api/v1/core/member/' . $member->id,
                ]);
                $req = Request::createFromEnvironment($env);
                $this->app->getContainer()['request'] = $req;
                $response = $this->app->run(false);

                $this->assertSame(204, $response->getStatusCode());
            }

        }while(sizeof($body->content) > 0);
    }
}