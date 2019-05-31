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

class MemberInscriptionDocumentTypeService
{
    /**
     * @var MemberInscriptionService
     */
    private $memberInscriptionService;

    /**
     * @var
     */
    private $kerosConfig;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var MemberInscriptionDocumentTypeDataService
     */
    protected $memberInscriptionDocumentTypeDataService;

    /**
     * @var DocumentGenerator
     */
    protected $documentGenerator;

    /**
     * MemberInscriptionDocumentTypeService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->memberInscriptionService = $container->get(MemberInscriptionService::class);
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
     * @param int $memberInscriptionId
     * @param int $documentTypeId
     * @return string
     * @throws KerosException
     */
    public function generateMemberInscriptionDocument(int $memberInscriptionId, int $documentTypeId): string
    {
        $memberInscription = $this->memberInscriptionService->getOne($memberInscriptionId);
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
     */
    public function getMemberInscriptionReplacementArray(MemberInscription $memberInscription): array
    {
        return array(
            'firstName' => $memberInscription->getFirstName(),
            'lastName' => $memberInscription->getLastName(),
            'year' => $memberInscription->getOutYear(),
            'nationality' => $memberInscription->getNationality()->getLabel(),
            'email' => $memberInscription->getEmail(),
            'phone' => $memberInscription->getPhoneNumber()
        );
    }

}