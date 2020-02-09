<?php

namespace KerosTest\Consultant;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use Keros\Tools\Helpers\FileHelper;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;

class ConsultantIntegrationTest extends AppTestCase
{
    public function testLikeSearchConsultantShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?search=Marah',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(2, $body->content[0]->id);
        $this->assertSame("Marah", $body->content[0]->firstName);
        $this->assertSame("Cool", $body->content[0]->lastName);
    }

    public function testSearchConsultantShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?firstName=Marah',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertNotNull($body->content);
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(1, sizeof($body->content));
        $this->assertSame(2, $body->content[0]->id);
        $this->assertSame("Marah", $body->content[0]->firstName);
        $this->assertSame("Cool", $body->content[0]->lastName);
    }

    public function testPutConnectedConsultantEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 2);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testPutConnectedConsultantShouldReturn200()
    {
        $put_body = array(
            "username" => "newusername",
            "password" => "password",
            "firstName" => "firstName",
            "lastName" => "lastName",
            "genderId" => 1,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "address" => [
                "line1" => "20 avenue albert Einstein",
                "line2" => "residence g",
                "city" => "lyon",
                "postalCode" => 69100,
                "countryId" => 1
            ],
            "schoolYear" => 1,
            "departmentId" => 1,
            "company" => "Amazon",
            "profilePicture" => "http://image.png",
            "droitImage" => true,
            "isApprentice" => true,
            "socialSecurityNumber" => "12346781300139041",
            'isGraduate' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/me',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 2);
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
        $this->assertSame("+332541541", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertSame("http://image.png", $body->profilePicture);
        $this->assertNotNull($body->address->id);
        $this->assertSame(true,$body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $this->assertSame(true, $body->isGraduate);
    }

    public function testDeleteConsultantShouldReturn204()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testDeleteInvalidConsultantShouldReturn404(){
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => '/api/v1/core/consultant/10',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());

    }

    public function testGetAllConsultantsShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant',
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

    public function testGetConsultantShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(2, $body->id);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("Marah", $body->firstName);
        $this->assertSame("Cool", $body->lastName);
        $this->assertSame("1976-10-27", $body->birthday);
        $this->assertSame("+332541541", $body->telephone);
        $this->assertSame("fake.mail2@fake.com", $body->email);
        $this->assertSame("Amazon", $body->company);
    }

    public function testGetConsultantProtectedDataShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant/2/protected',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(2, $body->id);
        $this->assertSame("123456789012345", $body->socialSecurityNumber);
    }

    public function testGetConnectedConsultantProtectedDataShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant/me/protected',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId", 2);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        $this->assertSame(2, $body->id);
        $this->assertSame("123456789012345", $body->socialSecurityNumber);
    }


    public function testGetConsultantShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant/10',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostConsultantShouldReturn201()
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
            "company" => "Amazon",
            "profilePicture" => "http://image.png",
            "droitImage" => true,
            "isApprentice" => true,
            "socialSecurityNumber" => "12346781300139041",
            'isGraduate' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/consultant',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
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
        $this->assertSame("http://image.png", $body->profilePicture);
        $this->assertSame(true,$body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $this->assertSame(true, $body->isGraduate);
    }
    
    public function testPutConsultantShouldReturn200()
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
            "profilePicture" => "http://image.png",
            "droitImage" => true,
            "isApprentice" => true,
            "socialSecurityNumber" => "12346781300139041",
            'isGraduate' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
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
        $this->assertSame("http://image.png", $body->profilePicture);
        $this->assertNotNull($body->address->id);
        $this->assertSame(true,$body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $this->assertSame(true, $body->isGraduate);
    }

    public function testPutConsultantOnlyRequiredFieldShouldReturn200()
    {
        $post_body = array(
            "username" => "newusername",
            "firstName" => "newfirstName",
            "lastName" => "newlastName",
            "genderId" => 2,
            "email" => "fakeEmail@gmail.com",
            "birthday" => "1975-12-01",
            "telephone" => "0033675385495",
            "address" => [
                "line1" => "20 avenue albert Einstein",
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
            "droitImage" => true,
            "isApprentice" => true,
            "socialSecurityNumber" => "12346781300139041",
            'isGraduate' => false,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        $this->assertNotNull($body->id);
        $this->assertSame("newusername", $body->username);
        $this->assertSame("newfirstName", $body->firstName);
        $this->assertSame("newlastName", $body->lastName);
        $this->assertSame(2, $body->gender->id);
        $this->assertSame("fakeEmail@gmail.com", $body->email);
        $this->assertSame("1975-12-01", $body->birthday);
        $this->assertSame(1, $body->department->id);
        $this->assertSame(1, $body->schoolYear);
        $this->assertSame("0033675385495", $body->telephone);
        $this->assertSame("Amazon", $body->company);
        $this->assertSame(null, $body->profilePicture);
        $this->assertNotNull($body->address->id);
        $this->assertSame(true,$body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $this->assertSame(false, $body->isGraduate);
    }

    public function testPutConsultantEmptyBodyShouldReturn400()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/core/consultant/2',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantDocumentShouldReturn201()
    {

        $filename = 'documentIdentity';
        $uploaded_files = array();

        $filename_ext = $filename . '.pdf';
        $temp_file = FileHelper::makeNewFile($filename_ext);
        $uploaded_files[$filename] = FileHelper::getUploadedFile($filename_ext);


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/core/consultant/2/document/$filename",
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withUploadedFiles($uploaded_files);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        FileHelper::closeFile($temp_file);
        FileHelper::deleteFile($filename_ext);

        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantDocumentUnacceptedFormatShouldReturn400()
    {

        $filename = 'documentIdentity';
        $uploaded_files = array();

        $filename_ext = $filename . '.exe';
        $temp_file = FileHelper::makeNewFile($filename_ext);
        $uploaded_files[$filename] = FileHelper::getUploadedFile($filename_ext);


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/core/consultant/2/document/$filename",
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withUploadedFiles($uploaded_files);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        FileHelper::closeFile($temp_file);
        FileHelper::deleteFile($filename_ext);

        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantDocumentShouldReturn404()
    {

        $filename = 'documentthatdoesntexist';
        $uploaded_files = array();

        $filename_ext = $filename . '.pdf';
        $temp_file = FileHelper::makeNewFile($filename_ext);
        $uploaded_files[$filename] = FileHelper::getUploadedFile($filename_ext);


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/core/consultant/2/document/$filename",
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withUploadedFiles($uploaded_files);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        FileHelper::closeFile($temp_file);
        FileHelper::deleteFile($filename_ext);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantDocumentShouldReturn400()
    {

        $filename = 'documentIdentity';
        $uploaded_files = array();


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/core/consultant/2/document/$filename",
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withUploadedFiles($uploaded_files);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testGetConsultantDocumentShouldReturn200()
    {
        $filename = 'documentIdentity';
        $file_path = "documents/consultant/identity/test.pdf";
        $temp_file = FileHelper::makeNewFile(FileHelper::normalizePath($file_path));
        FileHelper::closeFile($temp_file);

        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => "/api/v1/core/consultant/2/document/$filename",
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        FileHelper::deleteFile(FileHelper::normalizePath($file_path));
    }

    public function testGetConsultantDocumentShouldReturn404()
    {
        $filename = 'documentIdentity';

        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => "/api/v1/core/consultant/1/document/$filename",
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }

    public function testPostConsultantWithFilesShouldReturn201()
    {
        $filename_set = array('documentIdentity', 'documentScolaryCertificate', 'documentRIB', 'documentVitaleCard', 'documentResidencePermit', 'documentCVEC');
        $uploaded_files = array();
        $temp_files = array();
        $file_paths = array();

        foreach ($filename_set as $filename) {
            $filename_ext = $filename . '.pdf';
            $file_paths[$filename] = $filename_ext;
            $temp_files[$filename] = FileHelper::makeNewFile($filename_ext);
            $uploaded_files[$filename] = FileHelper::getUploadedFile($filename_ext);
        }

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
            "company" => "Amazon",
            "profilePicture" => "http://image.png",
            "droitImage" => true,
            "isApprentice" => true,
            "socialSecurityNumber" => "12346781300139041",
            'isGraduate' => false
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/core/consultant',
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($post_body);
        $req = $req->withUploadedFiles($uploaded_files);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        array_walk($temp_files, function ($temp_file) {
            FileHelper::closeFile($temp_file);
        });
        array_walk($file_paths, function ($file_path) {
            FileHelper::deleteFile($file_path);
        });

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
        $this->assertSame("http://image.png", $body->profilePicture);
        $this->assertSame(true,$body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $dateDiff = ((new \DateTime())->diff(new \DateTime($body->createdDate->date)))->format('%a');
        $this->assertSame(intval($dateDiff),0);
        $this->assertSame(false, $body->isGraduate);
    }

    public function testGetAllConsultantsPage0ShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?pageNumber=0',
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
        $this->assertSame(0, $body->meta->page);
        $this->assertSame(2, $body->meta->totalPages);
        $this->assertSame(29, $body->meta->totalItems);
        $this->assertSame(25, $body->meta->itemsPerPage);
    }

    public function testGetAllConsultantsPage1ShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?pageNumber=1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());
        $this->assertEquals(4, count($body->content));
        $this->assertNotNull(strlen($body->content[0]->id));
        $this->assertNotNull(strlen($body->content[0]->username));
        $this->assertNotNull(strlen($body->content[0]->firstName));
        $this->assertNotNull(strlen($body->content[0]->lastName));
        $this->assertNotNull(strlen($body->content[0]->gender->id));
        $this->assertNotNull(strlen($body->content[0]->email));
        $this->assertNotNull(strlen($body->content[0]->birthday));
        $this->assertNotNull(strlen($body->content[0]->address->id));
        $this->assertSame(1, $body->meta->page);
        $this->assertSame(2, $body->meta->totalPages);
        $this->assertSame(29, $body->meta->totalItems);
        $this->assertSame(25, $body->meta->itemsPerPage);
    }

    public function testGetConsultantsOrderByUsernameShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?orderBy=username',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $username = $body->content[0]->username;
        foreach ($body->content as $consultant){
            $this->assertGreaterThanOrEqual($username, $consultant->username);
            $username = $consultant->username;
        }
    }

    public function testGetConsultantsOrderByEmailShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/core/consultant?orderBy=email',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $email = $body->content[0]->email;
        foreach ($body->content as $consultant){
            $this->assertGreaterThanOrEqual($email, $consultant->email);
            $email = $consultant->email;
        }
    }
}