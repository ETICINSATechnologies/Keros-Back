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
     * @var
     */
    private $kerosConfig;

    /**
     * @var Logger
     */
    private $logger;

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
     * @var GenderBuilder
     */
    private $genderBuilder;

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
        $this->studyService = $container->get(StudyService::class);
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->memberService = $container->get(MemberService::class);
        $this->genderBuilder = $container->get(GenderBuilder::class);
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

    /**
     * @param Template $template
     * @param array $searchArray
     * @param array[] $replacementArrays Array of all replacement array
     * @return string
     * @throws KerosException
     */
    private function generateMultipleDocument(Template $template, array $searchArray, array $replacementArrays)
    {
        //generate file name until it doesn't not actually exist
        do {
            $zipLocation = $this->temporaryDirectory . md5($template->getName() . microtime()) . '.zip';
        } while (file_exists($zipLocation));
        $zip = new \ZipArchive();
        if ($zip->open($zipLocation, \ZipArchive::CREATE) !== TRUE) {
            $msg = "Error creating zip with template " . $template->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
        $files[] = array();
        //create document for each consultant
        $cpt = -1;
        foreach ($replacementArrays as $replacementArray) {
            $cpt++;
            //TODO use identifiant
            //$filename = $this->temporaryDirectory . pathinfo($template->getName(), PATHINFO_FILENAME) . '_' . $consultant->getId() . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
            $filename = $this->temporaryDirectory . pathinfo($template->getName(), PATHINFO_FILENAME) . '_' . $cpt . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
            $files[] = $filename;
            //copy template
            copy($template->getLocation(), $filename);

            //open document and replace pattern
            switch (pathinfo($template->getLocation(), PATHINFO_EXTENSION)) {
                case 'docx':
                    $return = $this->generateDocx($filename, $searchArray, $replacementArray);
                    break;
                case 'pptx':
                    $return = $this->generatePptx($filename, $searchArray, $replacementArray);
                    break;
                default :
                    $return = false;
            }

            if (!$return) {
                $msg = "Error generating document with template " . $template->getId();
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
        $location = $zipLocation;

        return $location;
    }

    /**
     * @param Template $template
     * @param array $searchArray
     * @param array $replacementArray
     * @return string
     * @throws KerosException
     */
    private function generateSimpleDocument(Template $template, array $searchArray, array $replacementArray)
    {
        do {
            $location = $this->temporaryDirectory . md5($template->getName() . microtime()) . '.' . pathinfo($template->getLocation(), PATHINFO_EXTENSION);
        } while (file_exists($location));

        copy($template->getLocation(), $location);

        switch (pathinfo($template->getLocation(), PATHINFO_EXTENSION)) {
            case 'docx':
                $return = $this->generateDocx($location, $searchArray, $replacementArray);
                break;
            case 'pptx':
                $return = $this->generatePptx($location, $searchArray, $replacementArray);
                break;
            default :
                $return = false;
        }

        if (!$return) {
            $msg = "Error generating document with template " . $template->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
        return $location;
    }

    /**
     * @param int $templateId
     * @param int $studyId
     * @param int $connectedUserId
     * @return string
     * @throws \Exception
     */
    public function generateStudyDocument(int $templateId, int $studyId, int $connectedUserId): string
    {
        $study = $this->studyService->getOne($studyId);
        $template = $this->getOne($templateId);
        $connectedUser = $this->memberService->getOne($connectedUserId);

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
        $searchArray = $this->getStudySearchArray();

        if ($doZip) {
            $replacementArrays = array();
            foreach ($study->getConsultantsArray() as $consultant)
                $replacementArrays[] = $this->getStudyReplacementArray($study, $connectedUser, array($consultant));
            $location = $this->generateMultipleDocument($template, $searchArray, $replacementArrays);
        } else {
            $replacementArray = $this->getStudyReplacementArray($study, $connectedUser, $study->getConsultantsArray());
            $this->logger->info(json_encode($replacementArray));
            $location = $this->generateSimpleDocument($template, $searchArray, $replacementArray);
        }
        return $location;
    }

    /**
     * @param $location
     * @param $searchArray
     * @param $replacementArray
     * @return bool
     */
    private function generateDocx($location, $searchArray, $replacementArray): bool
    {
        //docx are zip
        $zip = new \ZipArchive();
        $fileToModify = 'word/document.xml';

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
     * @param $searchArray
     * @param $replacementArray
     * @return bool
     */
    private function generatePptx($location, $searchArray, $replacementArray): bool
    {
        //pptx are zip. Same things like docx, just multiple xml to parse
        $zip = new \ZipArchive();

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
    public function getStudySearchArray(): array
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
    public function getStudyReplacementArray(Study $study, Member $connectedUser, array $consultants): array
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
                    if ($position->getPosition()->getId() == 23)
                        $tresorier = $position->getMember();
                    else if ($position->getPosition()->getId() == 14)
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
            ($president != null) ? $president->getFirstName() : '${PRENOMPRESIDENT}',
            ($tresorier != null) ? $tresorier->getLastName() : '${NOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) : '${CIVTRESORIER}',
            ($tresorier != null) ? $tresorier->getFirstName() : '${PRENOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) . ' ' . $tresorier->getLastName() . ' ' . $tresorier->getFirstName() : '${IDENTITETRESORIER}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) . ' ' . $president->getLastName() . ' ' . $president->getFirstName() : '${IDENTITEPRESIDENT}',
        );
    }

}