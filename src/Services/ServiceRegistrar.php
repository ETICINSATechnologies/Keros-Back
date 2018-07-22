<?php


namespace Keros\Services;

use Keros\Services\Core\AddressService;
use Keros\Services\Core\CountryService;
use Keros\Services\Core\DepartmentService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\PoleService;
use Keros\Services\Core\PositionService;
use Keros\Services\Ua\FirmTypeService;
use Psr\Container\ContainerInterface;

class ServiceRegistrar
{
    public static function registerServices(ContainerInterface $container)
    {
        // Core
        $container[AddressService::class] = function ($container) {
            return new AddressService($container);
        };
        $container[CountryService::class] = function ($container) {
            return new CountryService($container);
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

        //UA
        $container[FirmTypeService::class] = function ($container) {
            return new FirmTypeService($container);
        };


    }
}