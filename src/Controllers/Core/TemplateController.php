<?php

namespace Keros\Controllers\Core;


use Doctrine\ORM\EntityManager;
use Keros\DataServices\Core\TemplateDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Ua\Contact;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\TemplateService;
use Keros\Services\Ua\StudyService;
use Keros\Tools\ConfigLoader;
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
     * @var
     */
    private $memberService;

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
        $this->memberService = $container->get(MemberService::class);
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

        //TODO utiliser le MODEL_DIRECTORY du settings.ini pour placer le modèle
        // TODO valider la requete avant
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
     * https://stackoverflow.com/questions/19503653/how-to-extract-text-from-word-file-doc-docx-xlsx-pptx-php/19503654#19503654
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
        $connectedUser = $this->memberService->getOne($request->getAttribute("userId"));
        //TODO : add column in base in "core_template" to mark if it is for one consultant
        $templateWithOneConsultant = array('ARRM.docx', 'Avenant_Etudiant.docx', 'Demande_BV.docx', 'RM.docx');
        $doZip = count($study->getConsultantsArray()) > 1 && in_array($template->getName(), $templateWithOneConsultant);

        $this->logger->info($doZip);

        $this->logger->info(json_encode($doZip));
        if ($doZip) {
            do {
                $ziplocation = $this->temporaryDirectory . md5($template->getName() . microtime()) . '.zip';
            } while (file_exists($ziplocation));
            $zip = new \ZipArchive();
            if ($zip->open($ziplocation, \ZipArchive::CREATE) !== TRUE) {
                $msg = "Error creating zip with template " . $template->getId() . " and study " . $study->getId();
                $this->logger->error($msg);
                throw new KerosException($msg, 500);
            }
            $files[] = array();
            foreach ($study->getConsultantsArray() as $consultant) {
                $filename = $this->temporaryDirectory . pathinfo($template->getName(), PATHINFO_FILENAME) . '_' . $consultant->getId() . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
                $files[] = $filename;
                copy($template->getLocation(), $filename);
                $return = false;

                if (pathinfo($template->getLocation(), PATHINFO_EXTENSION) == 'docx')
                    $return = $this->generateStudyDocx($filename, $study, $connectedUser, $consultant);
                elseif (pathinfo($template->getLocation(), PATHINFO_EXTENSION) == 'pptx')
                    $return = $this->generateStudyPptx($filename, $study, $connectedUser, $consultant);
                if (!$return) {
                    $msg = "Error generating document with template " . $template->getId() . " and study " . $study->getId();
                    $this->logger->error($msg);
                    throw new KerosException($msg, 500);
                }

                $zip->addFile($filename, pathinfo($template->getName(), PATHINFO_FILENAME) . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_BASENAME));
            }
            $zip->close();
            foreach ($files as $filename)
                unlink($filename);
            $location = $ziplocation;
        } else {
            do {
                $location = $this->temporaryDirectory . md5($template->getName() . microtime()) . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
            } while (file_exists($location));

            copy($template->getLocation(), $location);

            $return = false;
            //TODO utiliser un switch
            if (pathinfo($template->getLocation(), PATHINFO_EXTENSION) == 'docx')
                $return = $this->generateStudyDocx($location, $study, $connectedUser, null);
            elseif (pathinfo($template->getLocation(), PATHINFO_EXTENSION) == 'pptx')
                $return = $this->generateStudyPptx($location, $study, $connectedUser, null);

            if (!$return) {
                $msg = "Error generating document with template " . $template->getId() . " and study " . $study->getId();
                $this->logger->error($msg);
                throw new KerosException($msg, 500);
            }
        }

        $kerosConfig = ConfigLoader::getConfig();
        $filename = pathinfo($location, PATHINFO_BASENAME);

        return $response->withJson(array('location' => $kerosConfig['BACKEND_URL'] . "/generated/" . $filename), 200);
    }

    /**
     * @param $location
     * @param $study
     * @param $connectedUser
     * @param $consultant
     * @return bool
     * @throws \Exception
     */
    private function generateStudyDocx($location, $study, $connectedUser, $consultant): bool
    {
        $zip = new \ZipArchive();
        $fileToModify = 'word/document.xml';

        $searchArray = $this->getSearchArray();
        $replacementArray = $this->getReplacementArray($study, $connectedUser, $consultant);

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
     * @param $connectedUser
     * @param $consultant
     * @return bool
     * @throws \Exception
     */
    private function generateStudyPptx($location, $study, $connectedUser, $consultant): bool
    {
        $zip = new \ZipArchive();

        $searchArray = $this->getSearchArray();
        $replacementArray = $this->getReplacementArray($study, $connectedUser, $consultant);


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
            '${FCTCONTACT}',
            '${CIVILITECONTACT}',
            '${PRENOMCONTACT}',
            '${NOMCONTACT}',
            '${MAILCONTACT}',
            '${DJOUR}',
            '${NUMINTERVENANT}',
            '${CIVILITEINTERVENANT}',
            '${PRENOMINTERVENANT}',
            '${NOMINTERVENANT}',
            '${ADRESSEINTERVENANT}',
            '${CPINTERVENANT}',
            '${VILLEINTERVENANT}',
            '${NOMUSER}',
            '${PRENOMUSER}',
            '${CIVILITEUSER}'
        );
    }

    /**
     * @param Study $study
     * @param Member $connectedUser
     * @param Member $consultant
     * @return array
     * @throws \Exception
     */
    private function getReplacementArray(Study $study, Member $connectedUser, ?Member $consultant): array
    {
        $contact = $study->getContacts()[0];
        $date = new DateTime();
        /* $consultantsNum = '';
         $consultantsFullAddress = '';
         $consultantsFullName = '';
         foreach ($study->getConsultantsArray() as $consultant) {
             $consultantsNum .= $consultant->getId() . " ";
             if ($consultant->getGender()->getLabel() == 'H')
                 $consultantsFullName .= 'M. ';
             elseif ($consultant->getGender()->getLabel() == 'F')
                 $consultantsFullName .= 'Mme ';
             else
                 $consultantsFullName .= '';
             $consultantsFullName .= $consultant->getFirstName() . " ";
             $consultantsFullName .= $consultant->getLastName() . ", ";
             $consultantsFullAddress .= $consultant->getAddress()->getLine1() . " " . $consultant->getAddress()->getLine2() . " ";
             $consultantsFullAddress .= $consultant->getAddress()->getPostalCode() . " ";
             $consultantsFullAddress .= $consultant->getAddress()->getCity() . ", ";
         }*/

        return array(
            $study->getFirm()->getName(),
            $study->getName(),
            $study->getFirm()->getAddress()->getLine1() . ", " . $study->getFirm()->getAddress()->getLine2(),
            $study->getFirm()->getAddress()->getPostalCode(),
            $study->getFirm()->getAddress()->getCity(),
            $study->getFirm()->getSiret(),
            $study->getDescription(),
            $study->getSignDate()->format('d/m/Y'),
            $contact->getPosition(),
            $contact->getGender()->getLabel() == 'H' ? "Monsieur" : ($contact->getGender()->getLabel() == 'F' ? "Madame" : ''),
            $contact->getFirstName(),
            $contact->getLastName(),
            $contact->getEmail(),
            $date->format('d/m/Y'),
            ($consultant != null) ? $consultant->getId() : '',
            ($consultant != null) ? ($consultant->getGender()->getLabel() == 'H' ? "Monsieur" : ($consultant->getGender()->getLabel() == 'F' ? "Madame" : '')) : '',
            ($consultant != null) ? $consultant->getFirstName() : '',
            ($consultant != null) ? $consultant->getLastName() : '',
            ($consultant != null) ? $consultant->getAddress()->getLine1() . ' ' . $consultant->getAddress()->getLine2() : '',
            ($consultant != null) ? $consultant->getAddress()->getPostalCode() : '',
            ($consultant != null) ? $consultant->getAddress()->getCity() : '',
            $connectedUser->getLastName(),
            $connectedUser->getFirstName(),
            $connectedUser->getGender()->getLabel() == 'H' ? "Monsieur" : ($connectedUser->getGender()->getLabel() == 'F' ? "Madame" : '')
        );
    }
}