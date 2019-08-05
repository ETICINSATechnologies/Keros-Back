<?php

namespace Keros\Tools;

use \ZipArchive;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use \Exception;
use mikehaertl\pdftk\Pdf;

/**
 * Lien pour le publipostage https://stackoverflow.com/questions/19503653/how-to-extract-text-from-word-file-doc-docx-xlsx-pptx-php/19503654#19503654
 * Class DocumentGenerator
 * @package Keros\Tools
 */
class DocumentGenerator
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var DirectoryManager
     */
    private $directoryManager;

    /**
     * @var string
     */
    private $documentTypeDirectory;

    /**
     * @var
     */
    protected $kerosConfig;

    /**
     * @var string
     */
    protected $temporaryDirectory;

    /**
     * DocumentGenerator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->documentTypeDirectory = $this->kerosConfig['DOCUMENT_TYPE_DIRECTORY'];
    }

    /**
     * @param $location
     * @param $searchArray
     * @param $replacementArray
     * @return bool
     */
    public function generateDocx($location, $searchArray, $replacementArray): bool
    {
        //docx are zip
        $zip = new ZipArchive();
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
    public function generatePptx($location, $searchArray, $replacementArray): bool
    {
        //pptx are zip. Same things like docx, just multiple xml to parse
        $zip = new ZipArchive();

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
     * @param $documentType
     * DocumentType to publiposte (FactureDocumentType, StudyDocumentType, MemberInscriptionDocumentType ...).
     * This DocumentType need to implement getLocation() (that return path to documentType) and getId()
     * @param array $replacementArray
     * @return string
     * @throws Exception
     */
    public function generateSimpleDocument($documentType, array $replacementArray)
    {
        $this->directoryManager->mkdir($this->kerosConfig["TEMPORARY_DIRECTORY"]);
        $documentTypeLocation = $this->documentTypeDirectory . $documentType->getLocation();
        $location = $this->directoryManager->uniqueFilename($documentType->getLocation(), false, $this->temporaryDirectory);

        if (!copy($documentTypeLocation, $location)) {
            $msg = "Error copying document type " . $documentType->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
        switch (pathinfo($documentTypeLocation, PATHINFO_EXTENSION)) {
            case 'docx':
                $return = $this->generateDocx($location, array_keys($replacementArray), array_values($replacementArray));
                break;
            case 'pptx':
                $return = $this->generatePptx($location, array_keys($replacementArray), array_values($replacementArray));
                break;
            case 'pdf':
                $return = $this->fillPdf($location, $documentTypeLocation, $replacementArray);
                break;
            default :
                $this->logger->error(pathinfo($documentTypeLocation, PATHINFO_EXTENSION) . " extension is not supported");
                $return = false;
        }

        if (!$return) {
            $msg = "Error generating document with document type " . $documentType->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }

        return $location;
    }

    /**
     * from https://www.sitepoint.com/filling-pdf-forms-pdftk-php/
     * and https://github.com/mikehaertl/php-pdftk
     * @param string $location
     * @param string $documentTypeLocation
     * @param array $replacementArray
     * @return bool
     * @throws KerosException
     */
    public function fillPdf(string $location, string $documentTypeLocation, array $replacementArray): bool
    {
        // Fill form with data array
        $pdf = new Pdf($documentTypeLocation);
        $pdf->fillForm(array_map(function ($val) {
            return utf8_decode($val);
        }, $replacementArray))
            ->needAppearances();

        // Check for errors
        if (!$pdf->saveAs($location)) {
            $msg = "Error generating pdf file " . $pdf->getError();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
        return true;
    }
}