<?php


namespace Keros\Controllers\Sg;


use Doctrine\ORM\EntityManager;
use Keros\Services\Sg\MemberInscriptionDocumentTypeService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use \Exception;

class MemberInscriptionDocumentController
{

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ConfigLoader
     */
    private $kerosConfig;

    /**
     * @var MemberInscriptionDocumentTypeService
     */
    private $memberInscriptionDocumentTypeService;

    /**
     * FactureDocumentController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->memberInscriptionDocumentTypeService = $container->get(MemberInscriptionDocumentTypeService::class);
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function generateDocument(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Generating document for memberInscription " . $args["id"] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $location = $this->memberInscriptionDocumentTypeService->generateMemberInscriptionDocument($args["id"], $args["documentTypeId"]);
        $filename = pathinfo($location, PATHINFO_BASENAME);

        return $response->withJson(array('location' => $this->kerosConfig['BACK_URL'] . "/generated/" . $filename), 200);
    }
}