<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\FieldDataService;
use Keros\Entities\Ua\Field;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class FieldService
{
    /**
     * @var FieldDataService
     */
    private $fieldDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->fieldDataService = $container->get(FieldDataService::class);
    }

    public function getOne(int $id): Field
    {
        $id = Validator::requiredId($id);

        $field = $this->fieldDataService->getOne($id);
        if (!$field) {
            throw new KerosException("The field could not be found", 404);
        }
        return $field;
    }

    public function getAll(): array
    {
        return $this->fieldDataService->getAll();
    }


}