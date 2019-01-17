<?php

namespace Keros\Services\Core;

use Keros\DataServices\Core\TemplateTypeDataService;
use Keros\Entities\Core\TemplateType;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class TemplateTypeService
{

    /**
     * @var TemplateTypeDataService
     */
    protected $templateTypeDataService;

    /**
     * TemplateTypeService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->templateTypeDataService = $container->get(TemplateTypeDataService::class);
    }

    /**
     * @param int $id
     * @return TemplateType
     * @throws KerosException
     */
    public function getOne(int $id): TemplateType
    {
        $id = Validator::requiredId($id);
        $templateType = $this->templateTypeDataService->getOne($id);
        if (!$templateType) {
            throw new KerosException("The TemplateType could not be found", 404);
        }
        return $templateType;
    }

    public function getAllTemplateType(): array
    {
        $templateType = $this->templateTypeDataService->getAll();
        return $templateType;
    }
}