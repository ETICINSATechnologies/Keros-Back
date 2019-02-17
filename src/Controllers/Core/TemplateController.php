<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\DataServices\Core\TemplateDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Ua\Study;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\TemplateService;
use Keros\Services\Ua\StudyService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\GenderBuilder;
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
     * @var MemberService
     */
    private $memberService;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var
     */
    private $backUrl;

    /**
     * @var GenderBuilder
     */
    private $genderBuilder;

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
        $this->memberService = $container->get(MemberService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->backUrl = $this->kerosConfig['BACK_URL'];
        $this->genderBuilder = $container->get(GenderBuilder::class);
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

        if ($study->getContacts() == null || empty($study->getContacts())) {
            $msg = "No contact in study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        if (!$this->studyService->consultantAreValid($study->getId())) {
            $msg = "Invalid consultant in study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        //Zip are done if one document per consultant is needed
        $doZip = $template->getOneConsultant() == 1;

        if ($doZip) {
            //generate file name until it doesn't not actually exist
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
            //create document for each consultant
            foreach ($study->getConsultantsArray() as $consultant) {
                $filename = $this->temporaryDirectory . pathinfo($template->getName(), PATHINFO_FILENAME) . '_' . $consultant->getId() . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
                $files[] = $filename;
                //copy template
                copy($template->getLocation(), $filename);

                //open document and replace pattern
                switch (pathinfo($template->getLocation(), PATHINFO_EXTENSION)) {
                    case 'docx':
                        $return = $this->generateStudyDocx($filename, $study, $connectedUser, array($consultant));
                        break;
                    case 'pptx':
                        $return = $this->generateStudyPptx($filename, $study, $connectedUser, array($consultant));
                        break;
                    default :
                        $return = false;
                }

                if (!$return) {
                    $msg = "Error generating document with template " . $template->getId() . " and study " . $study->getId();
                    $this->logger->error($msg);
                    throw new KerosException($msg, 500);
                }
                //move file with replaced pattern in zip archive
                $zip->addFile($filename, pathinfo($template->getName(), PATHINFO_FILENAME) . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_BASENAME));
            }
            $zip->close();
            //delete every temporary file
            foreach ($files as $filename)
                unlink($filename);
            $location = $ziplocation;

        } else {//similar than if statement above
            do {
                $location = $this->temporaryDirectory . md5($template->getName() . microtime()) . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
            } while (file_exists($location));

            copy($template->getLocation(), $location);

            switch (pathinfo($template->getLocation(), PATHINFO_EXTENSION)) {
                case 'docx':
                    $return = $this->generateStudyDocx($location, $study, $connectedUser, $study->getConsultantsArray());
                    break;
                case 'pptx':
                    $return = $this->generateStudyPptx($location, $study, $connectedUser, $study->getConsultantsArray());
                    break;
                default :
                    $return = false;
            }

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
        //docx are zip
        $zip = new \ZipArchive();
        $fileToModify = 'word/document.xml';

        $searchArray = $this->getSearchArray();
        $replacementArray = $this->getReplacementArray($study, $connectedUser, $consultant);

        if ($zip->open($location) === TRUE) {
            $oldContents = $zip->getFromName($fileToModify);
            //replace pattern
            $newContents = str_replace($searchArray, $replacementArray, $oldContents);

            $zip->deleteName($fileToModify);
            $zip->addFromString($fileToModify, $newContents);
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
        //pptx are zip. Same things like docx, just multiple xml to parse
        $zip = new \ZipArchive();
        $searchArray = $this->getSearchArray();
        $replacementArray = $this->getReplacementArray($study, $connectedUser, $consultant);

        if (true === $zip->open($location)) {
            $slide_number = 1;
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
     * To add pattern, add here and in :getReplacementArray AT THE SAME INDEX
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
            '${MAILINTERVENANT}',
            '${ADRESSEINTERVENANT}',
            '${CPINTERVENANT}',
            '${VILLEINTERVENANT}',
            '${NOMUSER}',
            '${PRENOMUSER}',
            '${CIVILITEUSER}',
            '${IDENTITECONTACT}',
            '${IDENTITEINTERVENANT}',
            '${INDENTITEUSER}',
            '${DATEFIN}',
            '${NOMPRESIDENT}',
            '${CIVPRESIDENT}',
            '${PRENOMPRESIDENT}',
            '${NOMTRESORIER}',
            '${CIVTRESORIER}',
            '${PRENOMTRESORIER}',
            '${IDENTITETRESORIER}',
            '${IDENTITEPRESIDENT}'
        );
    }

    /**
     * To add replacement, add here and in :getSearchArray AT THE SAME INDEX
     * @param Study $study
     * @param Member $connectedUser
     * @param Member[] $consultants
     * @return array
     * @throws \Exception
     */
    private function getReplacementArray(Study $study, Member $connectedUser, array $consultants): array
    {
        $contact = $study->getContacts()[0];
        $date = new DateTime();

        $consultantsIdentity = '';
        $nbConsultant = 0;
        //loop to have multiple consultant identity correctly
        foreach ($study->getConsultantsArray() as $consultant) {
            $consultantsIdentity .= $this->genderBuilder->getStringGender($consultant->getGender()) . ' ' . $consultant->getLastName() . ' ' . $consultant->getFirstName();
            //If we are not one the last consultant in the array
            if (++$nbConsultant !== count($study->getConsultantsArray()))
                $consultantsIdentity .= ', ';
        }

        //Information about actual board
        $tresorier = null;
        $president = null;
        $board = $this->memberService->getLatestBoard();
        foreach ($board as $member) {
            foreach ($member->getPositionsArray() as $position) {
                if ($position->getIsBoard()) {
                    if ($position->getPosition()->getLabel() == "Trésorier")
                        $tresorier = $position->getMember();
                    else if ($position->getPosition()->getLabel() == "Président")
                        $president = $position->getMember();
                }
            }
        }

        return array(
            $study->getFirm()->getName(),
            $study->getName(),
            $study->getFirm()->getAddress()->getLine1() . ", " . $study->getFirm()->getAddress()->getLine2(),
            $study->getFirm()->getAddress()->getPostalCode(),
            $study->getFirm()->getAddress()->getCity(),
            ($study->getFirm()->getSiret() != null) ? $study->getFirm()->getSiret() : '${SIRETENTREPRISE}',
            ($study->getDescription() != null) ? $study->getDescription() : '${DESCRIPTIONETUDE}',
            ($study->getSignDate() != null) ? $study->getSignDate()->format('d/m/Y') : '${DATESIGCV}',
            ($contact->getPosition() != null) ? $contact->getPosition() != null : '${FCTCONTACT}',
            $this->genderBuilder->getStringGender($contact->getGender()),
            $contact->getFirstName(),
            $contact->getLastName(),
            $contact->getEmail(),
            $date->format('d/m/Y'),
            ($consultants[0] != null) ? $consultants[0]->getId() : '${NUMINTERVENANT}',
            ($consultants[0] != null) ? $this->genderBuilder->getStringGender($consultants[0]->getGender()) : '${CIVILITEINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getFirstName() : '${PRENOMINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getLastName() : '${NOMINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getEmail() : '${MAILINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getLine1() . (($consultants[0]->getAddress()->getLine2() != null) ? ' ' . $consultants[0]->getAddress()->getLine2() : '') : '${ADRESSEINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getPostalCode() : '${CPINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getCity() : '${VILLEINTERVENANT}',
            $connectedUser->getLastName(),
            $connectedUser->getFirstName(),
            $this->genderBuilder->getStringGender($connectedUser->getGender()),
            $this->genderBuilder->getStringGender($contact->getGender()) . ' ' . $contact->getLastName() . ' ' . $contact->getFirstName(),
            $consultantsIdentity,
            $this->genderBuilder->getStringGender($connectedUser->getGender()) . ' ' . $connectedUser->getLastName() . ' ' . $connectedUser->getFirstName(),
            ($study->getArchivedDate() != null) ? $study->getArchivedDate()->format('d/m/Y') : '${DATEFIN}',
            ($president != null) ? $president->getLastName() : '${NOMPRESIDENT}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) : '${CIVPRESIDENT}',
            ($president != null) ? $president->getFirstName() : '${PRENOMPRESIDENT',
            ($tresorier != null) ? $tresorier->getLastName() : '${NOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) : '${CIVTRESORIER}',
            ($tresorier != null) ? $tresorier->getFirstName() : '${PRENOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) . ' ' . $tresorier->getLastName() . ' ' . $tresorier->getFirstName() : '${IDENTITETRESORIER}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) . ' ' . $president->getLastName() . ' ' . $president->getFirstName() : '${IDENTITEPRESIDENT}',
        );
    }
}