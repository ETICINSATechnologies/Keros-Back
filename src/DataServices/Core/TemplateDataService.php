<?php

namespace Keros\DataServices\Core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Core\Template;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerInterface;

class TemplateDataService
{

    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * @var
     */
    private $kerosConfig;

    /**
     * TemplateDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(Template::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
    }

    /**
     * @param int $id
     * @return Template|null
     * @throws KerosException
     */
    public function getOne(int $id): ?Template
    {
        try {
            $template = $this->repository->find($id);
            return $template;
        } catch (Exception $e) {
            $msg = "Error finding template with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll(): array
    {
        try {
            $template = $this->repository->findAll();
            return $template;
        } catch (Exception $e) {
            $msg = "Error finding templates : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }


    /**
     * @param Template $template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(Template $template)
    {
        try {
            $this->entityManager->persist($template);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to persist template : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param Template $template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Template $template)
    {
        try {
            $this->entityManager->remove($template);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $msg = "Failed to delete template : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }


    /**
     * @param Template $template
     * @param array $searchArray
     * @param array[] $replacementArrays Array of all replacement array
     * @return string
     * @throws KerosException
     */
    public function generateMultipleDocument(Template $template, array $searchArray, array $replacementArrays)
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
    public function generateSimpleDocument(Template $template, array $searchArray, array $replacementArray)
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

}