<?php

namespace Keros\Controllers\Core;


use Doctrine\ORM\EntityManager;
use Keros\DataServices\Core\TemplateDataService;
use Keros\Entities\Ua\Study;
use Keros\Services\Core\TemplateService;
use Keros\Services\Ua\StudyService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use DateTime;

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
     * @var StudyService
     */
    private $studyService;

    /**
     * @var
     */
    private $temporaryDirectory;

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
        $this->studyService = $container->get(StudyService::class);
        $this->temporaryDirectory = $container->get("temporaryDirectory");
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
        $body["typeId"] = intval($body["typeId"]);//TODO a supp, juste pour postman

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

    //https://stackoverflow.com/questions/41296206/read-and-replace-contents-in-docx-word-file//TODO a garder

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Keros\Error\KerosException
     * @throws \Exception
     */
    public function generateDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Generating document from template from " . $request->getServerParams()["REMOTE_ADDR"]);
        $study = $this->studyService->getOne($args["idStudy"]);
        $template = $this->templateService->getOne($args["idTemplate"]);

        $date = new DateTime();
        $location = $this->temporaryDirectory . $date->format('d-m-Y_H:i:s:u') . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);

        copy($template->getLocation(), $location);

        $searchArray = array(
            '${NOMENTREPRISE}',
            '${TITREETUDE}',
            '${ADRESSEENTREPRISE}',
            '${CPENTREPRISE}',
            '${VILLEENTREPRISE}',
            '${SIRETENTREPRISE}',
            '${DESCRIPTIONETUDE}',
            '${DATESIGCV}',
        );
        $replacementArray = array(
            $study->getFirm()->getName(),
            $study->getName(),
            $study->getFirm()->getAddress()->getLine1() . ", " . $study->getFirm()->getAddress()->getLine2(),
            $study->getFirm()->getAddress()->getPostalCode(),
            $study->getFirm()->getAddress()->getCity(),
            $study->getFirm()->getSiret(),
            $study->getDescription(),
            $study->getSignDate()->format('d/m/Y'),
        );

        //-----
        $zip = new \ZipArchive();

        //This is the main document in a .docx file.
        $fileToModify = 'word/document.xml';

        //$file = 'template.docx';//public_path

        if ($zip->open($location) === TRUE) {
            //Read contents into memory
            $oldContents = $zip->getFromName($fileToModify);

            //echo $oldContents;

            //Modify contents:
            $newContents = str_replace($searchArray, $replacementArray, $oldContents);

            //Delete the old...
            $zip->deleteName($fileToModify);
            //Write the new...
            $zip->addFromString($fileToModify, $newContents);
            //And write back to the filesystem.
            $return = $zip->close();
            If ($return == TRUE) {
                echo "Success!";
            }
        } else {
            echo 'failed';
        }
        //-----


        //$newLines = str_replace($searchArray, $replacementArray, $lines);
        //file_put_contents($location, $newLines, FILE_APPEND);

        return $response->withStatus(200);
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
}