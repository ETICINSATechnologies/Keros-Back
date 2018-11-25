<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\AddressDataService;
use Keros\Entities\Core\Address;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class AddressService
{
    /**
     * @var AddressDataService
     */
    private $addressDataService;
    /**
     * @var CountryService
     */
    private $countryService;

    public function __construct(ContainerInterface $container)
    {
        $this->addressDataService = $container->get(AddressDataService::class);
        $this->countryService = $container->get(CountryService::class);
    }

    public function create(array $fields): Address
    {
        $line1 = Validator::requiredString($fields["line1"]);
        $line2 = Validator::optionalString($fields["line2"]);
        $postalCode = Validator::requiredString($fields["postalCode"]);
        $city = Validator::requiredString($fields["city"]);
        $countryId = Validator::requiredId($fields["countryId"]);

        $country = $this->countryService->getOne($countryId);

        $address = new Address($line1, $line2, $postalCode, $city, $country);
        $this->addressDataService->persist($address);

        return $address;
    }

    public function update(int $id, ?array $fields)
    {
        $id = Validator::requiredId($id);
        $address = $this->getOne($id);

        $line1 = Validator::requiredString($fields["line1"]);
        $line2 = Validator::optionalString($fields["line2"]);
        $postalCode = Validator::requiredString($fields["postalCode"]);
        $city = Validator::requiredString($fields["city"]);
        $countryId = Validator::requiredId($fields["countryId"]);

        $country = $this->countryService->getOne($countryId);

        $address->setLine1($line1);
        $address->setLine2($line2);
        $address->setPostalCode($postalCode);
        $address->setCity($city);
        $address->setCountry($country);
        $this->addressDataService->persist($address);

        return $address;
    }

    public function getOne(int $id): Address
    {
        $id = Validator::requiredId($id);

        $address = $this->addressDataService->getOne($id);
        if (!$address) {
            throw new KerosException("The address could not be found", 404);
        }
        return $address;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->addressDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->addressDataService->getCount($requestParameters);
    }


}