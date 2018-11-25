<?php


namespace Keros\Services;

use Keros\Services\Ua\ProvenanceService;
use Keros\Services\Auth\LoginService;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\CountryService;
use Keros\Services\Core\DepartmentService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\PoleService;
use Keros\Services\Core\PositionService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\UserService;
use Keros\Services\Ua\ContactService;
use Keros\Services\Ua\FirmService;
use Keros\Services\Ua\FirmTypeService;
use Keros\Services\Ua\FieldService;
use Keros\Services\Ua\StatusService;
use Keros\Services\Ua\StudyService;
use Psr\Container\ContainerInterface;

class ServiceRegistrar
{
    public static function register(ContainerInterface $container)
    {
        // Auth
        $container[LoginService::class] = function ($container) {
            return new LoginService($container);
        };

        // Core
        $container[CountryService::class] = function ($container) {
            return new CountryService($container);
        };
        $container[AddressService::class] = function ($container) {
            return new AddressService($container);
        };
        $container[DepartmentService::class] = function ($container) {
            return new DepartmentService($container);
        };
        $container[GenderService::class] = function ($container) {
            return new GenderService($container);
        };
        $container[PoleService::class] = function ($container) {
            return new PoleService($container);
        };
        $container[PositionService::class] = function ($container) {
            return new PositionService($container);
        };
        $container[UserService::class] = function ($container) {
            return new UserService($container);
        };
        $container[MemberService::class] = function ($container) {
            return new MemberService($container);
        };

        //UA
        $container[FirmTypeService::class] = function ($container) {
            return new FirmTypeService($container);
        };
        $container[FirmService::class] = function ($container) {
            return new FirmService($container);
        };
        $container[ContactService::class] = function ($container) {
            return new ContactService($container);
        };
        $container[ProvenanceService::class] = function ($container) {
            return new ProvenanceService($container);
        };
        $container[FieldService::class] = function ($container)
        {
            return new FieldService($container);
        };
        $container[StatusService::class] = function ($container)
        {
            return new StatusService($container);
        };
        $container[StudyService::class] = function ($container) {
            return new StudyService($container);
        };
    }
}
