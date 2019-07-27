<?php

namespace Keros\Controllers\Treso;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Treso\PaymentSlip;
use Keros\Error\KerosException;
use Keros\Services\Treso\PaymentSlipService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Exception;

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
     * @var
     */
    private $kerosConfig;

    /**
     * PaymentSlipController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->paymentSlipService = $container->get(PaymentSlipService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function deletePaymentSlip(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->entityManager->beginTransaction();
        $this->paymentSlipService->delete($args['id']);
        $this->entityManager->commit();
        return $response->withStatus(204);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function getPaymentSlip(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting paymentSlip by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $paymentSlip = $this->paymentSlipService->getOne($args["id"]);

        return $response->withJson($paymentSlip, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function updatePaymentSlip(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating paymentSlip ".  $args['id'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->logger->info(json_encode($args));
        $paymentSlip = $this->paymentSlipService->update($args["id"], $request->getParsedBody());
        $this->entityManager->commit();

        return $response->withJson($paymentSlip, 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function validateUA(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating paymentSlip by UA from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $this->paymentSlipService->validateUA($args["id"], $request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function validatePerf(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Validating paymentSlip by Perf from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $this->paymentSlipService->validatePerf($args["id"], $request->getAttribute("userId"));
        $this->entityManager->commit();

        return $response->withStatus(200);
    }
}