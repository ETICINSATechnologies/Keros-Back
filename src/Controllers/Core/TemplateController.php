<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\DataServices\Core\TemplateDataService;
use Keros\Services\Core\TemplateService;
use Keros\Services\Ua\StudyTemplateService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TemplateController
{

    /**
     * @var TemplateService
     */
    private $templateService;

    /**
     * @var TemplateDataService
     */
    private $templateTypeService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var StudyTemplateService
     */
    private $studyTemplateService;

    /**
     * TemplateController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->templateService = $container->get(TemplateService::class);
        $this->templateTypeService = $container->get(TemplateDataService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->studyTemplateService = $container->get(StudyTemplateService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     */
    public function getTemplate(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting template by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $template = $this->templateService->getOne($args['id']);

        return $response->withJson($template, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Keros\Error\KerosException
     */
    public function createTemplate(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating template from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $uploadedFile = $request->getUploadedFiles()['file'];
        $body["extension"] = pathinfo($uploadedFile->getClientFileName(), PATHINFO_EXTENSION);

        $this->entityManager->beginTransaction();
        $template = $this->templateService->create($body);
        $uploadedFile->moveTo($template->getLocation());
        $this->entityManager->commit();

        return $response->withJson($template, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Keros\Error\KerosException
     */
    public function deleteTemplate(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting template from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->entityManager->beginTransaction();
        $this->templateService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     */
    public function getAllTemplate(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all Templates from " . $request->getServerParams()["REMOTE_ADDR"]);

        $templates = $this->templateService->getAll();

        return $response->withJson($templates, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    public function generateStudyDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Generating document with template " . $args["idTemplate"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $location = $this->studyTemplateService->generateStudyDocument($args["idTemplate"], $args["idStudy"], $request->getAttribute("userId"));
        $filename = pathinfo($location, PATHINFO_BASENAME);

        return $response->withJson(array('location' => $this->kerosConfig['BACKEND_URL'] . "/generated/" . $filename), 200);
    }


}