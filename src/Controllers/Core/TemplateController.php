<?php

namespace Keros\Controllers\Core;


use Doctrine\ORM\EntityManager;
use Keros\DataServices\Core\TemplateDataService;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
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
        mkdir(pathinfo($template->getLocation(), PATHINFO_DIRNAME), 0777, true);
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

    //https://stackoverflow.com/questions/19503653/how-to-extract-text-from-word-file-doc-docx-xlsx-pptx-php/19503654#19503654
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
        $this->logger->debug("Generating document with template " . $args["idTemplate"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);
        $study = $this->studyService->getOne($args["idStudy"]);
        $template = $this->templateService->getOne($args["idTemplate"]);

        $date = new DateTime();
        $location = $this->temporaryDirectory . $date->format('d-m-Y_H:i:s:u') . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
        mkdir(pathinfo($location, PATHINFO_DIRNAME), 0777, true);

        copy($template->getLocation(), $location);

        $return = false;

        if (pathinfo($template->getLocation(), PATHINFO_EXTENSION) == 'docx')
            $return = $this->generateStudyDocx($location, $study);
        elseif (pathinfo($template->getLocation(), PATHINFO_EXTENSION) == 'pptx')
            $return = $this->generateStudyPptx($location, $study);

        if (!$return) {
            $msg = "Error generating document with template " . $template->getId() . " and study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }

        $response = $response->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', 'attachment;filename="' . pathinfo($location, PATHINFO_BASENAME) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate')
            ->withHeader('Pragma', 'public')
            ->withHeader('Content-Length', filesize($location));
        readfile($location);

        return $response->withStatus(302);
    }

    /**
     * @param $location
     * @param $study
     * @return bool
     */
    private function generateStudyDocx($location, $study): bool
    {
        $zip = new \ZipArchive();
        $fileToModify = 'word/document.xml';

        $searchArray = $this->getSearchArray();
        $replacementArray = $this->getReplacementArray($study);

        if ($zip->open($location) === TRUE) {
            //Read contents into memory
            $oldContents = $zip->getFromName($fileToModify);

            //Modify contents:
            $newContents = str_replace($searchArray, $replacementArray, $oldContents);

            //Delete the old...
            $zip->deleteName($fileToModify);
            //Write the new...
            $zip->addFromString($fileToModify, $newContents);
            //And write back to the filesystem.
            $return = $zip->close();
            return $return;
        } else {
            return false;
        }
    }

    /**
     * @param $location
     * @param $study
     * @return bool
     */
    function generateStudyPptx($location, $study): bool
    {
        $zip = new \ZipArchive();

        $searchArray = $this->getSearchArray();
        $replacementArray = $this->getReplacementArray($study);

        if (true === $zip->open($location)) {
            $slide_number = 1; //loop through slide files
            while (($zip->locateName("ppt/slides/slide" . $slide_number . ".xml")) !== false) {

                $fileToModify = "ppt/slides/slide" . $slide_number . ".xml";
                $oldContents = $zip->getFromName("ppt/slides/slide" . $slide_number . ".xml");
                $newContents = str_replace($searchArray, $replacementArray, $oldContents);
                $zip->deleteName($fileToModify);
                $zip->addFromString($fileToModify, $newContents);
                $slide_number++;
            }
            return $zip->close();
        }
        return false;
    }

    /**
     * @return array
     */
    private function getSearchArray(): array
    {
        return array(
            '${NOMENTREPRISE}',
            '${TITREETUDE}',
            '${ADRESSEENTREPRISE}',
            '${CPENTREPRISE}',
            '${VILLEENTREPRISE}',
            '${SIRETENTREPRISE}',
            '${DESCRIPTIONETUDE}',
            '${DATESIGCV}',
        );
    }

    /**
     * @param Study $study
     * @return array
     */
    private function getReplacementArray(Study $study): array
    {
        return array(
            $study->getFirm()->getName(),
            $study->getName(),
            $study->getFirm()->getAddress()->getLine1() . ", " . $study->getFirm()->getAddress()->getLine2(),
            $study->getFirm()->getAddress()->getPostalCode(),
            $study->getFirm()->getAddress()->getCity(),
            $study->getFirm()->getSiret(),
            $study->getDescription(),
            $study->getSignDate()->format('d/m/Y'),
        );
    }
}