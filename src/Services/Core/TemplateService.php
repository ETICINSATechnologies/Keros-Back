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
use DateTime;
use Exception;

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
     * @var string
     */
    private $templateDirectory;

    /**
     * TemplateService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->templateDataService = $container->get(TemplateDataService::class);
        $this->templateTypeService = $container->get(TemplateTypeService::class);
        $this->templateDirectory = $container->get("templateDirectory");
    }

    /**
     * @param array $fields
     * @return Template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws Exception
     */
    public function create(array $fields): Template
    {
        $name = Validator::requiredString($fields["name"]);
        //$typeId = Validator::requiredId($fields["typeId"]); //TODO
        $extension = Validator::requiredString($fields["extension"]);

        $date = new DateTime();
        $location = $this->templateDirectory . $date->format('d-m-Y_H:i:s:u') . '.' . $extension;

        $templateType = $this->templateTypeService->getOne(1); //TODO $typeId
        $template = new Template($name, $location, $templateType);

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
    public function getAll(): array
    {
        return $this->templateDataService->getAll();
    }
}