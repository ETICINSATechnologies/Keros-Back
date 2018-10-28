<?php


namespace Keros\DataServices;

use Keros\DataServices\Auth\LoginDataService;
use Keros\DataServices\Core\AddressDataService;
use Keros\DataServices\Core\CountryDataService;
use Keros\DataServices\Core\DepartmentDataService;
use Keros\DataServices\Core\GenderDataService;
use Keros\DataServices\Core\MemberDataService;
use Keros\DataServices\Core\PoleDataService;
use Keros\DataServices\Core\PositionDataService;
use Keros\DataServices\Core\UserDataService;
use Keros\DataServices\Ua\FirmTypeDataService;
use Keros\DataServices\Ua\FirmDataService;
use Psr\Container\ContainerInterface;

class DataServiceRegistrar
{
    public static function registerServices(ContainerInterface $container)
    {
        // Auth
        $container[LoginDataService::class] = function ($container) {
            return new LoginDataService($container);
        };

        // Core
        $container[AddressDataService::class] = function ($container) {
            return new AddressDataService($container);
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

        //UA
        $container[FirmTypeDataService::class] = function ($container) {
            return new FirmTypeDataService($container);
        };
        $container[FirmDataService::class] = function ($container) {
            return new FirmDataService($container);
        };


    }
}
