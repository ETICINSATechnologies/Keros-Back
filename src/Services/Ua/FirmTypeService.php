<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\FirmTypeDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\FirmType;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class FirmTypeService
{
    /**
     * @var FirmTypeDataService
     */
    private $firmTypeDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->firmTypeDataService = $container->get(FirmTypeDataService::class);
    }

    public function getOne(int $id): FirmType
    {
        $id = Validator::requiredId($id);
        $firmType = $this->firmTypeDataService->getOne($id);
        if (!$firmType) {
            throw new KerosException("The FirmType could not be found", 404);
        }
        return $firmType;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        $firmTypes = $this->firmTypeDataService->getPage($requestParameters);
        return $firmTypes;
    }
}