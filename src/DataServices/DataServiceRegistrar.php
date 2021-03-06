<?php


namespace Keros\DataServices;

use Keros\DataServices\Core\AddressDataService;
use Keros\DataServices\Core\TicketDataService;
use Keros\DataServices\Core\CountryDataService;
use Keros\DataServices\Core\DepartmentDataService;
use Keros\DataServices\Core\GenderDataService;
use Keros\DataServices\Core\MemberDataService;
use Keros\DataServices\Core\MemberPositionDataService;
use Keros\DataServices\Core\PoleDataService;
use Keros\DataServices\Core\PositionDataService;
use Keros\DataServices\Core\UserDataService;
use Keros\DataServices\Sg\MemberInscriptionDataService;
use Keros\DataServices\Sg\ConsultantInscriptionDataService;
use Keros\DataServices\Sg\MemberInscriptionDocumentDataService;
use Keros\DataServices\Sg\MemberInscriptionDocumentTypeDataService;
use Keros\DataServices\Treso\FactureDocumentDataService;
use Keros\DataServices\Treso\FactureDocumentTypeDataService;
use Keros\DataServices\Treso\FactureTypeDataService;
use Keros\DataServices\Treso\FactureDataService;
use Keros\DataServices\Core\ConsultantDataService;
use Keros\DataServices\Ua\ContactDataService;
use Keros\DataServices\Ua\FirmTypeDataService;
use Keros\DataServices\Ua\FirmDataService;
use Keros\DataServices\Treso\PaymentSlipDataService;
use Keros\DataServices\Ua\ProvenanceDataService;
use Keros\DataServices\Ua\FieldDataService;
use Keros\DataServices\Ua\StatusDataService;
use Keros\DataServices\Ua\StudyDataService;
use Keros\DataServices\Ua\StudyDocumentTypeDataService;
use Keros\DataServices\Ua\StudyDocumentDataService;
use Psr\Container\ContainerInterface;

class DataServiceRegistrar
{
    public static function register(ContainerInterface $container)
    {
        // Core
        $container[AddressDataService::class] = function ($container) {
            return new AddressDataService($container);
        };
        $container[TicketDataService::class] = function ($container) {
            return new TicketDataService($container);
        };
        $container[CountryDataService::class] = function ($container) {
            return new CountryDataService($container);
        };
        $container[DepartmentDataService::class] = function ($container) {
            return new DepartmentDataService($container);
        };
        $container[GenderDataService::class] = function ($container) {
            return new GenderDataService($container);
        };
        $container[PoleDataService::class] = function ($container) {
            return new PoleDataService($container);
        };
        $container[PositionDataService::class] = function ($container) {
            return new PositionDataService($container);
        };
        $container[UserDataService::class] = function ($container) {
            return new UserDataService($container);
        };
        $container[MemberDataService::class] = function ($container) {
            return new MemberDataService($container);
        };
        $container[MemberPositionDataService::class] = function ($container) {
            return new MemberPositionDataService($container);
        };
        $container[StudyDocumentDataService::class] = function ($container) {
            return new StudyDocumentDataService($container);
        };
        $container[ConsultantDataService::class] = function ($container) {
            return new ConsultantDataService($container);
        };

        //UA
        $container[FirmTypeDataService::class] = function ($container) {
            return new FirmTypeDataService($container);
        };
        $container[FirmDataService::class] = function ($container) {
            return new FirmDataService($container);
        };
        $container[ContactDataService::class] = function ($container) {
            return new ContactDataService($container);
        };
        $container[ProvenanceDataService::class] = function ($container) {
            return new ProvenanceDataService($container);
        };
        $container[FieldDataService::class] = function ($container) {
            return new FieldDataService($container);
        };
        $container[StatusDataService::class] = function ($container) {
            return new StatusDataService($container);
        };
        $container[StudyDataService::class] = function ($container) {
            return new StudyDataService($container);
        };
        $container[StudyDocumentTypeDataService::class] = function ($container) {
            return new StudyDocumentTypeDataService($container);
        };
        $container[StudyDocumentDataService::class] = function ($container) {
            return new StudyDocumentDataService($container);
        };

        //Treso
        $container[PaymentSlipDataService::class] = function ($container) {
            return new PaymentSlipDataService($container);
        };
        $container[FactureTypeDataService::class] = function ($container) {
            return new FactureTypeDataService($container);
        };
        $container[FactureDataService::class] = function ($container) {
            return new FactureDataService($container);
        };
        $container[FactureDocumentTypeDataService::class] = function ($container) {
            return new FactureDocumentTypeDataService($container);
        };
        $container[FactureDocumentDataService::class] = function ($container) {
            return new FactureDocumentDataService($container);
        };

        //Sg
        $container[MemberInscriptionDataService::class] = function ($container) {
            return new MemberInscriptionDataService($container);
        };
        $container[ConsultantInscriptionDataService::class] = function ($container) {
            return new ConsultantInscriptionDataService($container);
        };
        $container[MemberInscriptionDocumentTypeDataService::class] = function ($container) {
            return new MemberInscriptionDocumentTypeDataService($container);
        };
        $container[MemberInscriptionDocumentDataService::class] = function ($container) {
            return new MemberInscriptionDocumentDataService($container);
        };
    }
}
