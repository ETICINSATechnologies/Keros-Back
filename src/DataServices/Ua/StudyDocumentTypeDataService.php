<?php

namespace Keros\DataServices\Ua;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Keros\Entities\Ua\StudyDocumentType;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DocumentGenerator;
use Monolog\Logger;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerInterface;

class StudyDocumentTypeDataService
{

    /**
     * @var EntityManager
     */
    protected $entityManager;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $temporaryDirectory;

    /**
     * @var
     */
    protected $kerosConfig;

    /**
     * @var DocumentGenerator
     */
    protected $documentGenerator;

    /**
     * @var string
     */
    private $studyDocumentTypeDirectory;

    /**
     * StudyDocumentTypeDataService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->repository = $this->entityManager->getRepository(StudyDocumentType::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->documentGenerator = $container->get(DocumentGenerator::class);
        $this->studyDocumentTypeDirectory = $this->kerosConfig['TEMPLATE_DIRECTORY'];
    }

    /**
     * @param int $id
     * @return StudyDocumentType|null
     * @throws KerosException
     */
    public function getOne(int $id): ?StudyDocumentType
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
     * @param StudyDocumentType $template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(StudyDocumentType $template)
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
     * @param StudyDocumentType $template
     * @throws KerosException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(StudyDocumentType $template)
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
     * @param StudyDocumentType $template
     * @param array $searchArray
     * @param array[] $replacementArrays Array of all replacement array
     * @return string
     * @throws KerosException
     */
    public function generateMultipleDocument(StudyDocumentType $template, array $searchArray, array $replacementArrays)
    {
        $documentTypeLocation = $this->studyDocumentTypeDirectory . $template->getLocation();

        //generate file name until it doesn't not actually exist
        do {
            $zipLocation = $this->temporaryDirectory . md5(pathinfo($documentTypeLocation, PATHINFO_BASENAME) . microtime()) . '.zip';
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
            $filename = $this->temporaryDirectory . pathinfo($documentTypeLocation, PATHINFO_FILENAME) . '_' . $cpt . '.' . pathinfo($documentTypeLocation, PATHINFO_EXTENSION);
            $files[] = $filename;
            //copy template
            copy($documentTypeLocation, $filename);

            //open document and replace pattern
            switch (pathinfo($documentTypeLocation, PATHINFO_EXTENSION)) {
                case 'docx':
                    $return = $this->documentGenerator->generateDocx($filename, $searchArray, $replacementArray);
                    break;
                case 'pptx':
                    $return = $this->documentGenerator->generatePptx($filename, $searchArray, $replacementArray);
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
            $zip->addFile($filename, pathinfo($documentTypeLocation, PATHINFO_FILENAME) . DIRECTORY_SEPARATOR . pathinfo($filename, PATHINFO_BASENAME));
        }
        $zip->close();
        //delete every temporary file
        foreach ($files as $filename)
            unlink($filename);
        $location = $zipLocation;

        return $location;
    }

    /**
     * @param StudyDocumentType $template
     * @param array $searchArray
     * @param array $replacementArray
     * @return string
     * @throws KerosException
     */
    public function generateSimpleDocument(StudyDocumentType $template, array $searchArray, array $replacementArray)
    {
        $documentTypeLocation = $this->studyDocumentTypeDirectory . DIRECTORY_SEPARATOR . $template->getLocation();
        do {
            $location = $this->temporaryDirectory . md5(pathinfo($documentTypeLocation, PATHINFO_BASENAME) . microtime()) . '.' . pathinfo($documentTypeLocation, PATHINFO_EXTENSION);
        } while (file_exists($location));

        copy($documentTypeLocation, $location);

        switch (pathinfo($documentTypeLocation, PATHINFO_EXTENSION)) {
            case 'docx':
                $return = $this->documentGenerator->generateDocx($location, $searchArray, $replacementArray);
                break;
            case 'pptx':
                $return = $this->documentGenerator->generatePptx($location, $searchArray, $replacementArray);
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

}