<?php

namespace Keros;

use Keros\Controllers\Auth\LoginController;
use Keros\Controllers\Core\AddressController;
use Keros\Controllers\Ua\StudyDocumentController;
use Keros\Controllers\Core\TicketController;
use Keros\Controllers\Core\CountryController;
use Keros\Controllers\Core\DepartmentController;
use Keros\Controllers\Core\GenderController;
use Keros\Controllers\Core\MemberController;
use Keros\Controllers\Core\PoleController;
use Keros\Controllers\Core\PositionController;
use Keros\Controllers\Core\TemplateController;
use Keros\Controllers\Core\TemplateTypeController;
use Keros\Controllers\Ua\ContactController;
use Keros\Controllers\Ua\FirmController;
use Keros\Controllers\Ua\FirmTypeController;
use Keros\Controllers\Ua\StudyController;
use Keros\DataServices\DataServiceRegistrar;
use Keros\Entities\Ua\Study;
use Keros\Error\ErrorHandler;
use Keros\Error\PhpErrorHandler;
use Keros\Services\ServiceRegistrar;
use Keros\Tools\Authorization\AuthenticationMiddleware;
use Keros\Tools\ConfigLoader;
use Keros\Tools\JwtCodec;
use Keros\Tools\KerosEntityManager;
use Keros\Tools\LoggerBuilder;
use Keros\Tools\PasswordEncryption;
use Keros\Tools\ToolRegistrar;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


/**
 * Class KerosApp - Main class ran by index.php. Used to configure
 * the Slim app
 * @package Keros
 */
class KerosApp
{
    /**
     * Stores an instance of the Slim application.
     * @var \Slim\App
     */
    private $app;

    /**
     * Prepares the container by adding the services as well as logger and other needed container parts
     * @param ContainerInterface $container the container to add the items to
     */
    public function prepareContainer(ContainerInterface $container)
    {
        ToolRegistrar::register($container);
        DataServiceRegistrar::register($container);
        ServiceRegistrar::register($container);
    }

    /**
     * KerosApp constructor. Configures the Slim App
     */
    public function __construct()
    {
        $app = new \Slim\App(['settings' => ConfigLoader::getConfig()]);

        $this->prepareContainer($app->getContainer());

        $app->group("/api/v1", function () {
            $this->get("/health", function (Request $request, Response $response, array $args) {
                $response->getBody()->write('{"health": "OK"}');
                return $response;
            });

            $this->group("/auth", function () {
                $this->post("/login", LoginController::class . ':login');
            });

            $this->group('/ua', function () {
                $this->group('/firm-type', function () {
                    $this->get("", FirmTypeController::class . ':getAllFirmType');
                    $this->get("/{id:[0-9]+}", FirmTypeController::class . ':getFirmType');
                });

                $this->group('/firm', function () {
                    $this->get("", FirmController::class . ':getPageFirms');
                    $this->get("/{id:[0-9]+}", FirmController::class . ':getFirm');
                    $this->post("", FirmController::class . ':createFirm');
                    $this->put("/{id:[0-9]+}", FirmController::class . ':updateFirm');
                    $this->delete("/{id:[0-9]+}", FirmController::class . ':deleteFirm');
                });

                $this->group('/contact', function () {
                    $this->get("", ContactController::class . ':getPageContact');
                    $this->get("/{id:[0-9]+}", ContactController::class . ':getContact');
                    $this->post("", ContactController::class . ':createContact');
                    $this->put("/{id:[0-9]+}", ContactController::class . ':updateContact');
                    $this->delete("/{id:[0-9]+}", ContactController::class . ':deleteContact');
                });

                $this->group('/study', function () {
                    $this->get("", StudyController::class . ':getPageStudy');
                    $this->get("/{id:[0-9]+}", StudyController::class . ':getStudy');
                    $this->get('/me', StudyController::class . ':getCurrentUserStudies');
                    $this->post("", StudyController::class . ':createStudy');
                    $this->put("/{id:[0-9]+}", StudyController::class . ':updateStudy');
                    $this->delete("/{id:[0-9]+}", StudyController::class . ':deleteStudy');
                    $this->get("/{idStudy:[0-9]+}/template/{idTemplate:[0-9]+}", TemplateController::class . ':generateStudyDocument');
                    $this->get("/{id:[0-9]+}/documents", StudyController::class . ':getAllDocuments');
                    $this->post("/{studyId:[0-9]+}/document/{documentId:[0-9]+}", StudyDocumentController::class . ':createDocument');
                    $this->get("/{studyId:[0-9]+}/document/{documentId:[0-9]+}", StudyDocumentController::class . ':getDocument');
                });
                $this->group('/provenance', function () {
                    $this->get("", StudyController::class . ':getAllProvenances');
                    $this->get("/{id:[0-9]+}", StudyController::class . ':getProvenance');
                });
                $this->group('/field', function () {
                    $this->get("", StudyController::class . ':getAllFields');
                    $this->get("/{id:[0-9]+}", StudyController::class . ':getField');
                });
                $this->group('/status', function () {
                    $this->get("", StudyController::class . ':getAllStatus');
                    $this->get("/{id:[0-9]+}", StudyController::class . ':getStatus');
                });

            })->add($this->getContainer()->get(AuthenticationMiddleware::class));

            $this->group('/core', function () {

                $this->group('/department', function () {
                    $this->get("", DepartmentController::class . ':getAllDepartments');
                    $this->get("/{id:[0-9]+}", DepartmentController::class . ':getDepartment');
                });

                $this->group('/gender', function () {
                    $this->get("", GenderController::class . ':getAllGenders');
                    $this->get("/{id:[0-9]+}", GenderController::class . ':getGender');
                });

                $this->group('/country', function () {
                    $this->get("", CountryController::class . ':getAllCountries');
                    $this->get("/{id:[0-9]+}", CountryController::class . ':getCountry');
                });

                $this->group('/pole', function () {
                    $this->get("", PoleController::class . ':getAllPoles');
                    $this->get("/{id:[0-9]+}", PoleController::class . ':getPole');
                });

                $this->group('/position', function () {
                    $this->get("", PositionController::class . ':getAllPositions');
                    $this->get("/{id:[0-9]+}", PositionController::class . ':getPosition');
                });

                $this->group('/member', function () {
                    $this->get("", MemberController::class . ':getPageMembers');
                    $this->get("/me", MemberController::class . ':getConnectedUser');
                    $this->put("/me", MemberController::class . ':updateConnectedUser');
                    $this->get('/{id:[0-9]+}', MemberController::class . ':getMember');
                    $this->post("", MemberController::class . ':createMember');
                    $this->put("/{id:[0-9]+}", MemberController::class . ':updateMember');
                    $this->delete("/{id:[0-9]+}", MemberController::class . ':deleteMember');
                    $this->get("/board/latest", MemberController::class . ':getLatestBoard');
                });

                $this->group('/ticket', function () {
                    $this->get("", TicketController::class . ':getPageTickets');
                    $this->get('/{id:[0-9]+}', TicketController::class . ':getTicket');
                    $this->post("", TicketController::class . ':createTicket');
                    $this->delete("/{id:[0-9]+}", TicketController::class . ':deleteTicket');
                });

                $this->group('/template-type', function () {
                    $this->get("", TemplateTypeController::class . ':getAllTemplateType');
                    $this->get('/{id:[0-9]+}', TemplateTypeController::class . ':getTemplateType');
                });

                $this->group('/template', function () {
                    $this->post("", TemplateController::class . ':createTemplate');
                    $this->get("", TemplateController::class . ':getAllTemplate');
                    $this->get('/{id:[0-9]+}', TemplateController::class . ':getTemplate');
                    $this->delete("/{id:[0-9]+}", TemplateController::class . ':deleteTemplate');
                });

            })->add($this->getContainer()->get(AuthenticationMiddleware::class));
        });

        $this->app = $app;
    }

    /**
     * Get an instance of the Slim app
     * @return \Slim\App
     */
    public function getApp()
    {
        return $this->app;
    }
}
