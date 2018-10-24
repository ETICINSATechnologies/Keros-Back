<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\FirmDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Firm;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class FirmService
{
    /**
     * @var AddressService
     */
    private $addressService;
    /**
     * @var FirmTypeService
     */
    private $firmTypeService;
    /**
     * @var FirmDataService
     */
    private $firmDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->addressService = $container->get(AddressService::class);
        $this->firmTypeService = $container->get(FirmTypeService::class);
        $this->firmDataService = $container->get(FirmDataService::class);
    }

    public function create(array $fields): Firm
    {
        $name = Validator::requiredString($fields["name"]);
        $siret = Validator::requiredString($fields["siret"]);
        $typeId = Validator::requiredId($fields["typeId"]);

        $address = $this->addressService->create($fields["address"]);
        $firmType = $this->firmTypeService->getOne($typeId);
        $firm = new Firm($name, $siret, $address, $firmType);

        $this->firmDataService->persist($firm);

        return $firm;
    }

    public function getOne(int $id): Firm
    {
        $id = Validator::requiredId($id);

        $firm = $this->firmDataService->getOne($id);
        if (!$firm) {
            throw new KerosException("The firm could not be found", 404);
        }
        return $firm;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->firmDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->firmDataService->getCount($requestParameters);
    }

    public function update(int $id, ?array $fields): Firm
    {
        $id = Validator::requiredId($id);
        $firm = $this->getOne($id);

        if (isset($fields["name"])) {
            $name = Validator::requiredString($fields["name"]);
            $firm->setName($name);
        }
        if (isset($fields["siret"])) {
            $siret = Validator::requiredString($fields["siret"]);
            $firm->setSiret($siret);
        }
        if (isset($fields["typeId"])) {
            $typeId = Validator::requiredInt($fields["typeId"]);
            $type = $this->firmTypeService->getOne($typeId);
            $firm->setType($type);
        }

        if (isset($fields["address"])) {
            $this->addressService->update($firm->getAddress()->getId(), $fields["address"]);
        }
        $this->firmDataService->persist($firm);

        return $firm;
    }
}