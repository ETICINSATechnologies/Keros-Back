<?php
/**
 * Created by PhpStorm.
 * User: paulgoux
 * Date: 2019-01-16
 * Time: 09:15
 */

namespace Keros\Services\Core;


use Keros\DataServices\Core\TemplateDataService;
use Keros\Entities\Core\Template;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class TemplateService
{
    /**
     * @var TemplateDataService
     */
    private $templateDataService;

    /**
     * @var TemplateTypeService
     */
    private $templateTypeService;

    /**
     * TemplateService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->templateDataService = $container->get(TemplateDataService::class);
        $this->templateTypeService = $container->get(TemplateTypeService::class);
    }

    /**
     * @param array $fields
     * @return Template
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Keros\Error\KerosException
     */
    public function create(array $fields): Template
    {
        $name = Validator::requiredString($fields["name"]);
        $typeId = Validator::requiredId($fields["typeId"]);

        $templateType = $this->templateTypeService->getOne($typeId);
        $template = new Template($name, $siret, $address, $templateType);

        $this->templateDataService->persist($template);

        return $template;
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Keros\Error\KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $template = $this->getOne($id);
        $this->templateDataService->delete($template);
    }

    /**
     * @param int $id
     * @return Template
     * @throws \Keros\Error\KerosException
     */
    public function getOne(int $id): Template
    {
        $id = Validator::requiredId($id);

        $template = $this->templateDataService->getOne($id);
        if (!$template) {
            throw new KerosException("The template could not be found", 404);
        }
        return $template;
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll() : array{
        return $this->templateDataService->getAll();
    }
}