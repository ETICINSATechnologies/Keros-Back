<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\CountryDataService;
use Keros\Entities\Core\Country;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class CountryService
{
    /**
     * @var CountryDataService
     */
    private $countryDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->countryDataService = $container->get(CountryDataService::class);
    }

    public function getOne(int $id): Country
    {
        $id = Validator::requiredId($id);
        $country = $this->countryDataService->getOne($id);
        if (!$country) {
            throw new KerosException("The country could not be found", 404);
        }
        return $country;
    }

    public function getAll(): array
    {
        return $this->countryDataService->getAll();
    }
}