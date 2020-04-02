<?php


namespace KerosTest\Sg;

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
        $this->assertSame(25, count($body->content));
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
        $this->assertSame(26, $body->meta->totalItems);
        $this->assertSame(2, $body->meta->totalPages);
        $this->assertSame(0, $body->meta->page);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPostConsultantInscriptionShouldReturn201()
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
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'phoneNumber' => '0033454653254',
            'outYear' => 2021,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
            'isApprentice' => true,
            "socialSecurityNumber" => "12346781300139041",
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
        $this->assertSame(2021, $body->outYear);
        $this->assertSame(133, $body->nationality->id);
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $dateDiff = ((new \DateTime())->diff(new \DateTime($body->createdDate->date)))->format('%a');
        $this->assertSame(intval($dateDiff),0);
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
            'outYear' => 2021,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
            'isApprentice' => true,
            "socialSecurityNumber" => "12346781300139041",
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

        $filename_set = array('documentIdentity', 'documentScolaryCertificate', 'documentRIB', 'documentVitaleCard', 'documentCVEC');
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
            'outYear' => 2021,
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
            'isApprentice' => true,
            "socialSecurityNumber" => "12346781300139041",
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
        $this->assertSame(2021, $body->outYear);
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->droitImage);
        $this->assertSame(true,$body->isApprentice);
        $dateDiff = ((new \DateTime())->diff(new \DateTime($body->createdDate->date)))->format('%a');
        $this->assertSame(intval($dateDiff),0);
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
        $this->assertSame(true,$body->isApprentice);
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
            'outYear' => 2021,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
            'isApprentice' => true,
            "socialSecurityNumber" => "12346781300139041",
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);
        $req = $req->withAttribute("userId",6);

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
        $this->assertSame(2021, $body->outYear);
        $this->assertSame(133, $body->nationality->id);
        $this->assertSame('je sais pas quoi mettre', $body->address->line1);
        $this->assertSame('Lorem ipsum', $body->address->city);
        $this->assertSame(true, $body->droitImage);
        $this->assertSame(true,$body->isApprentice);
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
            'outYear' => 2021,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
            'isApprentice' => true,
            "socialSecurityNumber" => "12346781300139041",
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1000',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);
        $req = $req->withAttribute("userId",6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testPutConsultantInscriptionShouldReturn401()
    {
        $put_body = array(
            'firstName' => 'Thanos',
            'lastName' => 'Tueur de monde',
            'genderId' => 4,
            'birthday' => '1000-01-01',
            'departmentId' => 4,
            'email' => 'thanos@claquementdedoigts.com',
            'phoneNumber' => '0033454653254',
            'outYear' => 2021,
            'nationalityId' => 133,
            'address' => array(
                'line1' => 'je sais pas quoi mettre',
                'line2' => "",
                'city' => 'Lorem ipsum',
                'postalCode' => 66666,
                'countryId' => 133,
            ),
            'droitImage' => true,
            'isApprentice' => true,
            "socialSecurityNumber" => "12346781300139041",
        );

        $env = Environment::mock([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withParsedBody($put_body);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(401, $response->getStatusCode());
    }


    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testDeleteConsultantInscriptionShouldReturn404()
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
    public function testDeleteConsultantInscriptionShouldReturn204()
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
    public function testPostConsultantInscriptionDocumentUnacceptedFormatShouldReturn400()
    {

        $filename = 'documentIdentity';
        $uploaded_files = array();

        $filename_ext = $filename . '.exe';
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

        $this->assertSame(400, $response->getStatusCode());
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

    public function testGetConsultantInscriptionDocumentShouldReturn200()
    {
        $filename = 'documentIdentity';
        $file_path = "documents/inscription/identity/test.pdf";
        $temp_file = FileHelper::makeNewFile(FileHelper::normalizePath($file_path));
        FileHelper::closeFile($temp_file);

        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => "/api/v1/sg/consultant-inscription/1/document/$filename",
        ]);

        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());
        FileHelper::deleteFile(FileHelper::normalizePath($file_path));
    }

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

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testValidateConsultantInscriptionShouldReturn204()
    {
        $filetypes = array('identity', 'residence_permit', 'rib', 'scolary_document', 'vitale_card', 'cvec');
        $filepaths = array();
        foreach ($filetypes as $fileType) {
            $file_path = "documents/inscription/$fileType/test.pdf";
            array_push($filepaths, $file_path);
            $temp_file = FileHelper::makeNewFile(FileHelper::normalizePath($file_path));
            FileHelper::closeFile($temp_file);
        }

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1/validate',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId",6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $body = json_decode($response->getBody());

        $this->assertSame(204, $response->getStatusCode());
        $this->assertNotNull($body->id);
        $this->assertSame('bruce.wayne', $body->username);
        $this->assertSame("Bruce", $body->firstName);
        $this->assertSame("Wayne", $body->lastName);
        $this->assertSame(1, $body->gender->id);
        $this->assertSame("bruce.wayne@batman.com", $body->email);
        $this->assertSame("2000-02-14", $body->birthday);
        $this->assertSame(3, $body->department->id);
        $this->assertSame("0033123456789", $body->telephone);
        $this->assertSame(null, $body->profilePicture);
        $this->assertNotNull($body->address->id);
        $this->assertSame('13 Rue du renard', $body->address->line1);
        $dateDiff = ((new \DateTime())->diff(new \DateTime($body->createdDate->date)))->format('%a');
        $this->assertSame(intval($dateDiff),0);

        foreach ($filepaths as $file_path) {
            FileHelper::deleteFile(FileHelper::normalizePath($file_path));
        }
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testValidateConsultantInscriptionShouldReturn401()
    {
        $filetypes = array('identity', 'residence_permit', 'rib', 'scolary_document', 'vitale_card', 'cvec');
        $filepaths = array();
        foreach ($filetypes as $fileType) {
            $file_path = "documents/inscription/$fileType/test.pdf";
            array_push($filepaths, $file_path);
            $temp_file = FileHelper::makeNewFile(FileHelper::normalizePath($file_path));
            FileHelper::closeFile($temp_file);
        }

        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1/validate',
        ]);
        $req = Request::createFromEnvironment($env);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testValidateConsultantInscriptionShouldReturn404()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1000/validate',
        ]);
        $req = Request::createFromEnvironment($env);
        $req = $req->withAttribute("userId",6);

        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetPage0ConsultantInscriptionShouldReturn200()
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
        $this->assertSame(25, count($body->content));
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

        $this->assertSame(0, $body->meta->page);
        $this->assertSame(2, $body->meta->totalPages);
        $this->assertSame(26, $body->meta->totalItems);
        $this->assertSame(25, $body->meta->itemsPerPage);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function testGetPage1ConsultantInscriptionShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?pageNumber=1',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $body = json_decode($response->getBody());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(1, count($body->content));
    }


    public function testGetConsultantInscriptionFilterFirstNameShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?firstName=Clark',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        foreach ($body->content as $consultantInscription){
            $this->assertContains('Clark', $consultantInscription->firstName);
        }
    }

    public function testGetConsultantInscriptionFilterLastNameShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?lastName=Wayne',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        foreach ($body->content as $consultantInscription){
            $this->assertContains('Wayne', $consultantInscription->lastName);
        }
    }

    public function testGetConsultantInscriptionFilterEmailShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?email=bruce.wayne@batman.com',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        foreach ($body->content as $consultantInscription){
            $this->assertContains('bruce.wayne@batman.com', $consultantInscription->email);
        }
    }

    public function testGetConsultantInscriptionFilterPhoneNumberShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?phoneNumber=0033123456789',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        foreach ($body->content as $consultantInscription){
            $this->assertContains('0033123456789', $consultantInscription->phoneNumber);
        }
    }

    public function testGetConsultantInscriptionFilterOutYearShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?outYear=2022',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        foreach ($body->content as $consultantInscription){
            $this->assertEquals(2022, $consultantInscription->outYear);
        }
    }

    public function testGetConsultantInscriptionSearchShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription?search=Cla',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getBody());

        foreach ($body->content as $consultantInscription){
            $this->assertContains('Clark', array($consultantInscription->firstName, $consultantInscription->lastName, $consultantInscription->phoneNumber, $consultantInscription->email));
        }
    }

    public function testGetConsultantInscriptionProtectedShouldReturn200()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/sg/consultant-inscription/1/protected',
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
        $this->assertSame(true,$body->isApprentice);
        $this->assertSame('12345678901234567', $body->socialSecurityNumber);
    }
}
