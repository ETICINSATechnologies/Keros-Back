<?php

namespace Keros\Services\Sg;

use Keros\DataServices\Sg\MemberInscriptionDocumentTypeDataService;
use Keros\Entities\Sg\MemberInscription;
use Keros\Entities\Sg\MemberInscriptionDocumentType;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DocumentGenerator;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use \Exception;
use \DateTime;

class MemberInscriptionDocumentTypeService
{
    /** @var */
    private $kerosConfig;

    /** @var Logger */
    protected $logger;

    /** @var MemberInscriptionDocumentTypeDataService */
    protected $memberInscriptionDocumentTypeDataService;

    /** @var DocumentGenerator */
    protected $documentGenerator;

    /**
     * MemberInscriptionDocumentTypeService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->memberInscriptionDocumentTypeDataService = $container->get(MemberInscriptionDocumentTypeDataService::class);
        $this->documentGenerator = $container->get(DocumentGenerator::class);
    }

    /**
     * @param int $id
     * @return MemberInscriptionDocumentType
     * @throws KerosException
     */
    public function getOne(int $id): MemberInscriptionDocumentType
    {
        $id = Validator::requiredId($id);

        $documentType = $this->memberInscriptionDocumentTypeDataService->getOne($id);
        if (!$documentType) {
            throw new KerosException("The memberInscriptionDocumentType could not be found", 404);
        }
        return $documentType;
    }

    /**
     * @return MemberInscriptionDocumentType[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->memberInscriptionDocumentTypeDataService->getAll();
    }

    /**
     * @param MemberInscription $memberInscription
     * @param int $documentTypeId
     * @return string
     * @throws Exception
     */
    public function generateMemberInscriptionDocument(MemberInscription $memberInscription, int $documentTypeId): string
    {
        $documentType = $this->getOne($documentTypeId);

        if (!$documentType->getisTemplatable()) {
            $msg = "Member-inscription document type " . $documentType->getId() . " is not templatable";
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        $replacementArray = $this->getMemberInscriptionReplacementArray($memberInscription);
        $location = $this->documentGenerator->generateSimpleDocument($documentType, $replacementArray);

        return $location;
    }

    /**
     * @param MemberInscription $memberInscription
     * @return array
     * @throws Exception
     */
    public function getMemberInscriptionReplacementArray(MemberInscription $memberInscription): array
    {
        $date = new DateTime();
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));
        $schoolYear = null;
        if ($memberInscription->getOutYear()) {
            $schoolYear = 5 - ($memberInscription->getOutYear() - $year);
            if ($month > 8 && $month <= 12) //between September and December
                $schoolYear += 1;
        }
        switch ($memberInscription->getWantedPole()->getId()) {
            case (1) :
                $wantedPole = "comm";
                break;
            case (3):
                $wantedPole = "devCo";
                break;
            case (4):
                $wantedPole = "perf";
                break;
            case (6):
                $wantedPole = "rh";
                break;
            case (7):
                $wantedPole = "si";
                break;
            case (8):
                $wantedPole = "treso";
                break;
            case (9):
                $wantedPole = "affaires";
                break;
            default:
                $wantedPole = "Off";
        }
        return array(
            'lastName' => $memberInscription->getLastName(),
            'firstName' => $memberInscription->getFirstName(),
            'email' => $memberInscription->getEmail(),
            'address.line1' => $memberInscription->getAddress()->getLine1() . ' ' . $memberInscription->getAddress()->getLine2(),
            'address.line2' => $memberInscription->getAddress()->getCity() . ' ' . $memberInscription->getAddress()->getPostalCode() . ' ' . $memberInscription->getAddress()->getCountry()->getLabel(),
            'annee_departement' => ($schoolYear) . $memberInscription->getDepartment()->getLabel(),
            'nationalite' => $memberInscription->getNationality()->getLabel(),
            'phoneNumber' => $memberInscription->getPhoneNumber(),
            'outYear' => $memberInscription->getOutYear(),
            'wantedPole' => $wantedPole,
            'RI' => "Yes",
            'RSE' => "Yes",
            'donnees' => "Yes",
            'photo' => ($memberInscription->isDroitImage() ? "Yes" : "Off"),
        );
    }

}