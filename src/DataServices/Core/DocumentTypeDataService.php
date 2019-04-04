<?php

namespace Keros\DataServices\Core;

use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class DocumentTypeDataService
{

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $temporaryDirectory;

    /**
     * @var
     */
    protected $kerosConfig;

    /**
     * DocumentTypeDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
    }

    /**
     * @param $location
     * @param $searchArray
     * @param $replacementArray
     * @return bool
     */
    protected function generateDocx($location, $searchArray, $replacementArray): bool
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
    protected function generatePptx($location, $searchArray, $replacementArray): bool
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

}