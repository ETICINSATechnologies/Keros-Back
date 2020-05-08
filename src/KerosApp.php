<?php

namespace Keros;

use Keros\Controllers\Auth\LoginController;
use Keros\Controllers\Sg\MemberInscriptionController;
use Keros\Controllers\Sg\MemberInscriptionDocumentController;
use Keros\Controllers\Sg\ConsultantInscriptionController;
use Keros\Controllers\Treso\FactureDocumentController;
use Keros\Controllers\Core\ConsultantController;
use Keros\Controllers\Ua\StudyDocumentController;
use Keros\Controllers\Core\TicketController;
use Keros\Controllers\Core\CountryController;
use Keros\Controllers\Core\DepartmentController;
use Keros\Controllers\Core\GenderController;
use Keros\Controllers\Core\MemberController;
use Keros\Controllers\Core\PoleController;
use Keros\Controllers\Core\PositionController;
use Keros\Controllers\Treso\PaymentSlipController;
use Keros\Controllers\Treso\FactureController;
use Keros\Controllers\Treso\FactureTypeController;
use Keros\Controllers\Ua\ContactController;
use Keros\Controllers\Ua\FirmController;
use Keros\Controllers\Ua\FirmTypeController;
use Keros\Controllers\Ua\StudyController;
use Keros\DataServices\DataServiceRegistrar;
use Keros\Services\ServiceRegistrar;
use Keros\Services\Auth\AccessRightsService;
use Keros\Tools\Authorization\AuthenticationMiddleware;
use Keros\Tools\ConfigLoader;
use Keros\Tools\JwtCodec;
use Keros\Tools\KerosEntityManager;
use Keros\Tools\PasswordEncryption;
use Keros\Tools\ToolRegistrar;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Stripe\Stripe;


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

            $this->group("/reset-password-member", function () {
                $this->post("", LoginController::class . ':resetPasswordMember');
            });


            $this->group("/forgot-password-member", function () {
                $this->post("", LoginController::class . ':forgotMemberPassword');
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
                    $this->get("/{idStudy:[0-9]+}/document/{idDocumentType:[0-9]+}/generate", StudyDocumentController::class . ':generateStudyDocument');
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
            })
            ->add(AccessRightsService::class . ":checkRightsNotAlumni")
            ->add($this->getContainer()->get(AuthenticationMiddleware::class));

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
                    $this->post("/{id:[0-9]+}/photo", MemberController::class . ':createProfilePicture');
                    $this->get("/{id:[0-9]+}/photo", MemberController::class . ':getProfilePicture');
                    $this->post("/me/photo", MemberController::class . ':createCurrentUserProfilePicture');
                    $this->get("/me/photo", MemberController::class . ':getCurrentUserProfilePicture');
					$this->delete("/{id:[0-9]+}/photo", MemberController::class . ':deleteProfilePicture');
					$this->post("/export", MemberController::class . ':exportMembers');
                });

                $this->group('/consultant', function () {
                    $this->get("", ConsultantController::class . ':getPageConsultants');
                    $this->get("/me", ConsultantController::class . ':getConnectedConsultant');
                    $this->put("/me", ConsultantController::class . ':updateConnectedConsultant');
                    $this->get('/{id:[0-9]+}', ConsultantController::class . ':getConsultant');
                    $this->post("", ConsultantController::class . ':createConsultant');
                    $this->put("/{id:[0-9]+}", ConsultantController::class . ':updateConsultant');
                    $this->delete("/{id:[0-9]+}", ConsultantController::class . ':deleteConsultant');
                    $this->get("/{id:[0-9]+}/document/{document_name:[a-zA-Z]+}", ConsultantController::class . ':getDocument');
                    $this->post("/{id:[0-9]+}/document/{document_name:[a-zA-Z]+}", ConsultantController::class . ':createDocument');
                    $this->get("/{id:[0-9]+}/protected", ConsultantController::class . ':getConsultantProtectedData');
                    $this->get("/me/protected", ConsultantController::class . ':getConnectedConsultantProtectedData');
					$this->post("/export", ConsultantController::class . ':exportConsultants');
                });

                $this->group('/ticket', function () {
                    $this->get("", TicketController::class . ':getPageTickets');
                    $this->get('/{id:[0-9]+}', TicketController::class . ':getTicket');
                    $this->post("", TicketController::class . ':createTicket');
                    $this->delete("/{id:[0-9]+}", TicketController::class . ':deleteTicket');
                });

            })->add($this->getContainer()->get(AuthenticationMiddleware::class));

            $this->post("/core/member/paid", MemberController::class. ':updatePaymentDate');

            $this->group('/treso', function () {

                $this->group('/facture-types', function () {
                    $this->get("", FactureTypeController::class . ':getAllFactureTypes');
                });
                $this->group('/facture', function () {
                    $this->get("", FactureController::class . ':getPageFacture');
                    $this->post("", FactureController::class . ':createFacture');
                    $this->get('/{id:[0-9]+}', FactureController::class . ':getFacture');
                    $this->delete("/{id:[0-9]+}", FactureController::class . ':deleteFacture');
                    $this->put("/{id:[0-9]+}", FactureController::class . ':updateFacture');
                    $this->post("/{id:[0-9]+}/validate-ua", FactureController::class . ':validateFactureByUa');
                    $this->post("/{id:[0-9]+}/validate-perf", FactureController::class . ':validateFactureByPerf');
                    $this->get("/{idFacture:[0-9]+}/generateDocument", FactureDocumentController::class . ':generateFactureDocument');
                });
                $this->group('/payment-slip', function () {
                    $this->post("", PaymentSlipController::class . ':createPaymentSlip');
                    $this->get("", PaymentSlipController::class . ':getPagePaymentSlip');
                    $this->get("/{id:[0-9]+}", PaymentSlipController::class . ':getPaymentSlip');
                    $this->delete("/{id:[0-9]+}", PaymentSlipController::class . ':deletePaymentSlip');
                    $this->put("/{id:[0-9]+}", PaymentSlipController::class . ':updatePaymentSlip');
                    $this->post("/{id:[0-9]+}/validate-ua", PaymentSlipController::class . ':validateUA');
                    $this->post("/{id:[0-9]+}/validate-perf", PaymentSlipController::class . ':validatePerf');
                });
            })
            ->add(AccessRightsService::class . ":checkRightsNotAlumni")
            ->add($this->getContainer()->get(AuthenticationMiddleware::class));

            $this->group('/sg', function () {
                $this->group('/membre-inscription', function () {
                    $this->get("", MemberInscriptionController::class . ':getPageMemberInscriptions');
                    $this->post("", MemberInscriptionController::class . ':createMemberInscription');
                    $this->get('/{id:[0-9]+}', MemberInscriptionController::class . ':getMemberInscription');
                    $this->delete("/{id:[0-9]+}", MemberInscriptionController::class . ':deleteMemberInscription');
                    $this->put("/{id:[0-9]+}", MemberInscriptionController::class . ':updateMemberInscription');
                    $this->post("/{id:[0-9]+}/validate", MemberInscriptionController::class . ':validateMemberInscription');
                    $this->post("/{id:[0-9]+}/confirm-payment", MemberInscriptionController::class . ':confirmPaymentMemberInscription');
                    $this->get("/{id:[0-9]+}/document/{documentTypeId:[0-9]+}/generate", MemberInscriptionDocumentController::class . ':generateDocument');
                    $this->post("/{id:[0-9]+}/document/{documentId:[0-9]+}", MemberInscriptionDocumentController::class . ':createDocument');
                    $this->get("/{id:[0-9]+}/document/{documentId:[0-9]+}", MemberInscriptionDocumentController::class . ':getDocument');
                });
                $this->group('/consultant-inscription', function () {
                    $this->get("", ConsultantInscriptionController::class . ':getPageConsultantInscriptions');
                    $this->post("", ConsultantInscriptionController::class . ':createConsultantInscription');
                    $this->get('/{id:[0-9]+}', ConsultantInscriptionController::class . ':getConsultantInscription');
                    $this->get('/{id:[0-9]+}/protected', ConsultantInscriptionController::class.':getConsultantInscriptionProtected');
                    $this->delete("/{id:[0-9]+}", ConsultantInscriptionController::class . ':deleteConsultantInscription');
                    $this->put("/{id:[0-9]+}", ConsultantInscriptionController::class . ':updateConsultantInscription');
                    $this->post("/{id:[0-9]+}/validate", ConsultantInscriptionController::class . ':validateConsultantInscription');
                    $this->get("/{id:[0-9]+}/document/{document_name:[a-zA-Z]+}", ConsultantInscriptionController::class . ':getDocument');
                    $this->post("/{id:[0-9]+}/document/{document_name:[a-zA-Z]+}", ConsultantInscriptionController::class . ':createDocument');
                });
            })
            ->add(AccessRightsService::class . ":checkRightsNotAlumni")
            ->add($this->getContainer()->get(AuthenticationMiddleware::class));
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
