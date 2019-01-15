<?php

namespace Keros\Controllers\Core;

use Keros\Services\Core\TemplateTypeService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TemplateTypeController
{

    /**
     * @var TemplateTypeService
     */
    private $templateTypeService;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->templateTypeService = $container->get(TemplateTypeService::class);
    }

    public function getTemplateType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting TemplateType by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $templateType = $this->templateTypeService->getOne($args["id"]);

        return $response->withJson($templateType, 200);
    }

    public function getAllTemplateType(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all TemplateTypes from " . $request->getServerParams()["REMOTE_ADDR"]);

        $templateTypes = $this->templateTypeService->getAllTemplateType();

        return $response->withJson($templateTypes, 200);
    }
}