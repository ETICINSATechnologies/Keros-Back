<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\TemplateDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\Template;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
use Keros\Services\Ua\StudyService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\GenderBuilder;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use DateTime;
use Exception;

/**
 * Lien pour le publipostage https://stackoverflow.com/questions/19503653/how-to-extract-text-from-word-file-doc-docx-xlsx-pptx-php/19503654#19503654
 * Class TemplateService
 * @package Keros\Services\Core
 */
class TemplateService
{
    /**
     * @var TemplateDataService
     */
    protected $templateDataService;

    /**
     * @var TemplateTypeService
     */
    private $templateTypeService;

    /**
     * @var string
     */
    private $templateDirectory;

    /**
     * @var
     */
    private $kerosConfig;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * TemplateService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->templateDataService = $container->get(TemplateDataService::class);
        $this->templateTypeService = $container->get(TemplateTypeService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->templateDirectory = $this->kerosConfig['TEMPLATE_DIRECTORY'];
        $this->logger = $container->get(Logger::class);
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
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
        $typeId = Validator::requiredId(intval($fields["typeId"]));
        $oneConsultant = Validator::requiredBool(boolval($fields["oneConsultant"]));
        $extension = Validator::requiredString($fields["extension"]);

        $date = new DateTime();
        $location = $this->templateDirectory . $date->format('d-m-Y_H:i:s:u') . '.' . $extension;

        $templateType = $this->templateTypeService->getOne($typeId);
        $template = new Template($name, $location, $templateType, $oneConsultant);

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
     * @return Template[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->templateDataService->getAll();
    }



}