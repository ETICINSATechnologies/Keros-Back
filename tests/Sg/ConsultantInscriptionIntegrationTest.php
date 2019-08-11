<?php


namespace KerosTest\Sg_temp;

use KerosTest\AppTestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use \Slim\Exception\MethodNotAllowedException;
use \Slim\Exception\NotFoundException;
use Keros\Tools\Helpers\FileHelper;

class ConsultantInscriptionIntegrationTest extends AppTestCase
{
    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetPageConsultantInscriptionShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription',
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
        $this->assertSame(1, $body->content[0]->address->id);
        $this->assertSame(false, $body->content[0]->droitImage);
        $this->assertSame(2, $body->meta->totalItems);
        $this->assertSame(1, $body->meta->totalPages);
        $this->assertSame(0, $body->meta->page);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantInscriptionShouldReturn201()
    {

        $filename_set = array('documentIdentity', 'documentScolaryCertificate', 'documentRIB', 'documentVitaleCard', 'documentResidencePermit');
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
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'phoneNumber' => '0033454653254',
            'outYear' => 6666,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription',
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
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->droitImage);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantInscriptionMissingFileShouldReturn400()
    {

        $filename_set = array('documentScolaryCertificate', 'documentRIB', 'documentVitaleCard', 'documentResidencePermit');
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
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'phoneNumber' => '0033454653254',
            'outYear' => 6666,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription',
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

        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantInscriptionOnlyRequiredFieldsShouldReturn201()
    {

        $filename_set = array('documentIdentity', 'documentScolaryCertificate', 'documentRIB', 'documentVitaleCard');
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
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription',
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
        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('Thanos', $body->firstName);
        $this->assertSame('Tueur de monde', $body->lastName);
        $this->assertSame(4, $body->gender->id);
        $this->assertSame('1000-01-01', $body->birthday);
        $this->assertSame(4, $body->department->id);
        $this->assertSame('thanos@claquementdedoigts.com', $body->email);
        $this->assertSame(133, $body->nationality->id);
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->droitImage);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetConsultantInscriptionShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1',
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
        $this->assertSame(1, $body->address->id);
        $this->assertSame(false, $body->droitImage);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetConsultantInscriptionShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1000',
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
    public function testPutConsultantInscriptionShouldReturn200()
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
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1',
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
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->droitImage);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPutConsultantInscriptionShouldReturn404()
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
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1000',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);
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
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1000',
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
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1',
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
    public function testPostConsultantInscriptionDocumentShouldReturn201()
    {

        $filename = 'documentIdentity';
        $uploaded_files = array();

        $filename_ext = $filename . '.pdf';
        $temp_file = FileHelper::makeNewFile($filename_ext);
        $uploaded_files[$filename] = FileHelper::getUploadedFile($filename_ext);


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/sg/consultant-inscription/2/document/$filename",
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
    public function testPostConsultantInscriptionDocumentShouldReturn404()
    {

        $filename = 'documentthatdoesntexist';
        $uploaded_files = array();

        $filename_ext = $filename . '.pdf';
        $temp_file = FileHelper::makeNewFile($filename_ext);
        $uploaded_files[$filename] = FileHelper::getUploadedFile($filename_ext);


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/sg/consultant-inscription/2/document/$filename",
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
    public function testPostConsultantInscriptionDocumentShouldReturn400()
    {

        $filename = 'documentIdentity';
        $uploaded_files = array();
        $temp_files = array();


        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => "/api/v1/sg/consultant-inscription/2/document/$filename",
        ]);

        $req = Request::createFromEnvironment($env);
        $req = $req->withUploadedFiles($uploaded_files);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(400, $response->getStatusCode());
    }

    // public function testGetConsultantInscriptionDocumentShouldReturn200()
    // {
    //     $fileName = "documentIdentity.pdf";
        
    //     $env = Environment::mock([
    //         'REQUEST_METHOD' => 'GET',
    //         'REQUEST_URI' => "/api/v1/sg/consultant-inscription/2/document/$fileName",
    //     ]);
    //     $req = Request::createFromEnvironment($env);
    //     $this->app->getContainer()['request'] = $req;
    //     $response = $this->app->run(false);
    //     $this->assertSame(200, $response->getStatusCode());
    // }

    public function testGetConsultantInscriptionDocumentShouldReturn404()
    {
        $filename = 'documentIdentity';

        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => "/api/v1/sg/consultant-inscription/2/document/$filename",
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(404, $response->getStatusCode());
    }
}
