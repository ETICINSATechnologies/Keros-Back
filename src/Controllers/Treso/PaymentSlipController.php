<?php

namespace Keros\Controllers\Treso;


use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\PaymentSlip;
use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\TemplateService;
use Keros\Services\Ua\FieldService;
use Keros\Services\Ua\ProvenanceService;
use Keros\Services\Ua\StatusService;
use Keros\Services\Treso\PaymentSlipService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class PaymentSlipController
{

    /**
     * @var PaymentSlipService
     */
    private $paymentSlipService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProvenanceService
     */
    private $provenanceService;
    /**
     * @var FieldService
     */
    private $fieldService;
    /**
     * @var StatusService
     */
    private $statusService;

    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var TemplateService
     */
    private $templateService;

    /**
     * @var
     */
    private $kerosConfig;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->paymentSlipService = $container->get(PaymentSlipService::class);
        $this->provenanceService = $container->get(ProvenanceService::class);
        $this->fieldService = $container->get(FieldService::class);
        $this->statusService = $container->get(StatusService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->templateService = $container->get(TemplateService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    public function getPaymentSlip(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting paymentSlip by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $paymentSlip = $this->paymentSlipService->getOne($args["id"]);

        return $response->withJson($paymentSlip, 200);
    }

    public function getAllStudies(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get studies " . $request->getServerParams()["REMOTE_ADDR"]);

        $studies = $this->paymentSlipService->getAll();

        return $response->withJson($studies, 200);
    }

    public function getPagePaymentSlip(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page studies from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, PaymentSlip::getSearchFields());

        $paymentSlip = $this->paymentSlipService->getPage($params);
        $totalCount = $this->paymentSlipService->getCount($params);

        $page = new Page($paymentSlip, $params, $totalCount);

        return $response->withJson($page, 200);
    }

    public function createPaymentSlip(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating paymentSlip from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $body['createdBy'] = $request->getAttribute("userId");
        $this->entityManager->beginTransaction();

        $paymentSlip = $this->paymentSlipService->create($body);
        $this->entityManager->commit();

        return $response->withJson($paymentSlip, 201);
    }

    public function validateUA(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating paymentSlip by UA from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $paymentSlip = $this->paymentSlipService->validateUA($args["id"],$request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withJson($paymentSlip, 200);
    }

    public function validatePerf(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating paymentSlip by Perf from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $paymentSlip = $this->paymentSlipService->validatePerf($args["id"],$request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withJson($paymentSlip, 200);
    }
    public function getField(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting field by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $field = $this->fieldService->getOne($args["id"]);

        return $response->withJson($field, 200);
    }

    public function getAllFields(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get fields " . $request->getServerParams()["REMOTE_ADDR"]);

        $fields = $this->fieldService->getAll();

        return $response->withJson($fields, 200);
    }

    public function getStatus(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting status by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $status = $this->statusService->getOne($args["id"]);

        return $response->withJson($status, 200);
    }

    public function getAllStatus(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get status " . $request->getServerParams()["REMOTE_ADDR"]);

        $status = $this->statusService->getAll();

        return $response->withJson($status, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws \Keros\Error\KerosException
     */
    public function getAllDocuments(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get all documents for paymentSlip " . $args["id"] . " " . $request->getServerParams()["REMOTE_ADDR"]);

        if (!$this->paymentSlipService->consultantAreValid($args["id"]))
            throw new KerosException("Invalid consultant in paymentSlip", 400);

        $templates = array();
        foreach ($this->templateService->getAll() as $template) {
            $templates[] = array('id' => $template->getId(),
                'name' => $template->getName(),
                'generateLocation' => $this->kerosConfig["BACK_URL"] . "/api/v1/ua/paymentSlip/" . $args["id"] . "/template/" . $template->getId());
        }

        return $response->withJson(array('documents' => $templates), 200);
    }
}