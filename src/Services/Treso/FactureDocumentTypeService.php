<?php

namespace Keros\Services\Treso;

use Keros\DataServices\Treso\FactureDocumentTypeDataService;
use Keros\Entities\Treso\FactureDocumentType;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\GenderBuilder;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Entities\Treso\Facture;
use Keros\Entities\Core\Member;
use DateTime;
use Exception;
use \Doctrine\ORM\OptimisticLockException;
use \Doctrine\ORM\ORMException;

class FactureDocumentTypeService
{

    /**
     * @var FactureService
     */
    private $factureService;

    /**
     * @var GenderBuilder
     */
    private $genderBuilder;

    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var
     */
    private $kerosConfig;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    private $documentTypeDirectory;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * @var FactureDocumentTypeDataService
     */
    protected $factureDocumentTypeDataService;

    /**
     * @var DirectoryManager
     */
    protected $directoryManager;

    public function __construct(ContainerInterface $container)
    {
        $this->factureService = $container->get(FactureService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->genderBuilder = $container->get(GenderBuilder::class);
        $this->logger = $container->get(Logger::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->temporaryDirectory = $this->kerosConfig['TEMPORARY_DIRECTORY'];
        $this->documentTypeDirectory = $this->kerosConfig['DOCUMENT_TYPE_DIRECTORY'];
        $this->factureDocumentTypeDataService = $container->get(FactureDocumentTypeDataService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
    }

    /**
     * @param array $fields
     * @return FactureDocumentType
     * @throws KerosException
     * @throws Exception
     */
    public function create(array $fields): FactureDocumentType
    {
        $isTemplatable = Validator::requiredBool($fields["isTemplatable"]);
        $name = Validator::requiredString($fields["name"]);
        $extension = Validator::requiredString($fields["extension"]);

        $date = new DateTime();
        $location = $date->format('d-m-Y_H:i:s:u') . '.' . $extension;
        $documentType = new FactureDocumentType($name, $location, $isTemplatable);

        $this->factureDocumentTypeDataService->persist($documentType);

        return $documentType;
    }

    /**
     * @param int $id
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $documentType = $this->getOne($id);
        $this->factureDocumentTypeDataService->delete($documentType);
    }

    /**
     * @param int $id
     * @return FactureDocumentType
     * @throws KerosException
     */
    public function getOne(int $id): FactureDocumentType
    {
        $id = Validator::requiredId($id);

        $documentType = $this->factureDocumentTypeDataService->getOne($id);
        if (!$documentType) {
            throw new KerosException("The factureDocumentType could not be found", 404);
        }
        return $documentType;
    }

    /**
     * @return FactureDocumentType[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->factureDocumentTypeDataService->getAll();
    }

    /**
     * @param int $factureId
     * @param int $connectedUserId
     * @return string
     * @throws Exception
     */
    public function generateFactureDocument(int $factureId, int $connectedUserId): string
    {
        $facture = $this->factureService->getOne($factureId);
        $documentTypes = $this->getAll();

        $documentType = null;
        foreach ($documentTypes as $documentT)
            if ($documentT->getFactureType() != null && $facture->getType()->getId() == $documentT->getFactureType()->getId())
                $documentType = $documentT;
        $connectedUser = $this->memberService->getOne($connectedUserId);

        if ($documentType == null) {
            $msg = "No document type found for facture " . $facture->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        if (!$documentType->getisTemplatable()) {
            $msg = "Document type " . $documentType->getId() . " is not templatable";
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        $this->directoryManager->mkdir($this->kerosConfig["TEMPORARY_DIRECTORY"]);
        $searchArray = $this->getFactureSearchArray();

        $replacementArray = $this->getFactureReplacementArray($facture, $connectedUser);
        $location = $this->factureDocumentTypeDataService->generateSimpleDocument($documentType, $searchArray, $replacementArray);

        return $location;
    }

    /**
     * To add pattern, add here and in :getReplacementArray AT THE SAME INDEX
     * @return array
     */
    public function getFactureSearchArray(): array
    {
        return array(
            '${NOMENTREPRISE}',
            '${TITREETUDE}',
            '${ADRESSEENTREPRISE}',
            '${CPENTREPRISE}',
            '${VILLEENTREPRISE}',
            '${PAYSENTREPRISE}',
            '${SIRETENTREPRISE}',
            '${DESCRIPTIONETUDE}',
            '${DATESIGCV}',
            '${DJOUR}',
            '${NOMUSER}',
            '${PRENOMUSER}',
            '${CIVILITEUSER}',
            '${IDENTITECONTACT}',
            '${INDENTITEUSER}',
            '${DATEFIN}',
            '${NOMPRESIDENT}',
            '${CIVPRESIDENT}',
            '${PRENOMPRESIDENT}',
            '${NOMTRESORIER}',
            '${CIVTRESORIER}',
            '${PRENOMTRESORIER}',
            '${IDENTITETRESORIER}',
            '${IDENTITEPRESIDENT}',
            '${NUMEROFACTURE}',
            '${MONTANTLETTREFACTURE}',
            '${MONTANTTTCFACTURE}',
            '${MONTANTHTFACTURE}',
            '${TVAFACTURE}',
            '${DUEDATE}',
            '${CREATEDDATE}',
            '${MONTANTTVAFACTURE}',
            '${POURCENTAGEFACTURE}',
            '${FENUMEROFACTURE}'
        );
    }

    /**
     * To add replacement, add here and in :getSearchArray AT THE SAME INDEX
     * @param Facture $facture
     * @param Member $connectedUser
     * @return array
     * @throws Exception
     */
    public function getFactureReplacementArray(Facture $facture, Member $connectedUser): array
    {
        $date = new DateTime();

        //Information about actual board
        $tresorier = null;
        $president = null;
        $board = $this->memberService->getLatestBoard();
        foreach ($board as $member) {
            foreach ($member->getMemberPositionsArray() as $position) {
                if ($position->getIsBoard()) {
                    if ($position->getPosition()->getId() == 23)
                        $tresorier = $position->getMember();
                    else if ($position->getPosition()->getId() == 14)
                        $president = $position->getMember();
                }
            }
        }

        $study = $facture->getStudy();
        $totalFacture = $study->getEcoParticipationFee() + $study->getManagementFee() + $study->getOutsourcingFee() + $study->getRealizationFee() + $study->getRebilledFee();
        $factureInCurrentMonth = $this->factureService->getAll();
        $feMonthNumber = 1;
        foreach ($factureInCurrentMonth as $factureInMonth) {
            if ($factureInMonth->getCreatedDate()->format('m/Y') == $facture->getCreatedDate()->format('m/Y') && $factureInMonth->getCreatedDate() < $facture->getCreatedDate())
                $feMonthNumber++;
        }

        return array(
            $facture->getClientName(),
            $facture->getSubject(),
            $facture->getFullAddress()->getLine1() . (($facture->getFullAddress()->getLine2() != null) ? ", " . $facture->getFullAddress()->getLine2() : ""),
            $facture->getFullAddress()->getPostalCode(),
            $facture->getFullAddress()->getCity(),
            $facture->getFullAddress()->getCountry()->getLabel(),
            ($study->getFirm()->getSiret() != null) ? $facture->getStudy()->getFirm()->getSiret() : '${SIRETENTREPRISE}',
            ($study->getDescription() != null) ? $facture->getStudy()->getDescription() : '${DESCRIPTIONETUDE}',
            ($facture->getAgreementSignDate() != null) ? $facture->getAgreementSignDate()->format('d/m/Y') : '${DATESIGCV}',
            $date->format('d/m/Y'),
            $connectedUser->getLastName(),
            $connectedUser->getFirstName(),
            $this->genderBuilder->getStringGender($connectedUser->getGender()),
            $facture->getContactName(),
            $this->genderBuilder->getStringGender($connectedUser->getGender()) . ' ' . $connectedUser->getLastName() . ' ' . $connectedUser->getFirstName(),
            ($facture->getStudy()->getArchivedDate() != null) ? $facture->getStudy()->getArchivedDate()->format('d/m/Y') : '${DATEFIN}',
            ($president != null) ? $president->getLastName() : '${NOMPRESIDENT}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) : '${CIVPRESIDENT}',
            ($president != null) ? $president->getFirstName() : '${PRENOMPRESIDENT}',
            ($tresorier != null) ? $tresorier->getLastName() : '${NOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) : '${CIVTRESORIER}',
            ($tresorier != null) ? $tresorier->getFirstName() : '${PRENOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) . ' ' . $tresorier->getLastName() . ' ' . $tresorier->getFirstName() : '${IDENTITETRESORIER}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) . ' ' . $president->getLastName() . ' ' . $president->getFirstName() : '${IDENTITEPRESIDENT}',
            ($facture->getNumero() != null) ? $facture->getNumero() : '${NUMEROFACTURE}',
            ($facture->getAmountDescription() != null) ? $facture->getAmountDescription() : '${MONTANTLETTREFACTURE}',
            ($facture->getAmountTTC() != null) ? $facture->getAmountTTC() : '${MONTANTTTCFACTURE}',
            ($facture->getAmountHT() != null) ? $facture->getAmountHT() : '${MONTANTHTFACTURE}',
            ($facture->getTaxPercentage() != null) ? $facture->getTaxPercentage() : '${TVAFACTURE}',
            ($facture->getDueDate() != null) ? $facture->getDueDate()->format('d/m/Y') : '${DUEDATE}',
            ($facture->getCreatedDate() != null) ? $facture->getCreatedDate()->format('d/m/Y') : '${CREATEDDATE}',
            ($facture->getAmountTTC() != null && $facture->getAmountHT() != null) ? $facture->getAmountTTC() - $facture->getAmountHT() : '${MONTANTTVAFACTURE}',
            number_format($facture->getAmountHT() * 100 / $totalFacture, 2),
            'FE' . $facture->getCreatedDate()->format('Y') . $facture->getCreatedDate()->format('m') . (($feMonthNumber < 10) ? "0" : "") . $feMonthNumber,
        );
    }
}