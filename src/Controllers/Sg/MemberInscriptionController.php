<?php

namespace Keros\Controllers\Sg;

use Doctrine\ORM\EntityManager;
use Keros\Entities\Core\Page;
use Keros\Entities\Sg\MemberInscription;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Sg\MemberInscriptionDocumentService;
use Keros\Services\Sg\MemberInscriptionDocumentTypeService;
use Keros\Services\Sg\MemberInscriptionService;
use Keros\Services\Auth\AccessRightsService;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MemberInscriptionController
{
    /** @var MemberInscriptionService */
    private $memberInscriptionService;

    /** @var Logger */
    private $logger;

    /** @var EntityManager */
    private $entityManager;

    /** @var MemberInscriptionDocumentTypeService */
    private $memberInscriptionDocumentTypeService;

    /** @var MemberInscriptionDocumentService */
    private $memberInscriptionDocumentService;

    /** @var AccessRightsService */
    private $accessRightsService;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->memberInscriptionService = $container->get(MemberInscriptionService::class);
        $this->memberInscriptionDocumentTypeService = $container->get(MemberInscriptionDocumentTypeService::class);
        $this->memberInscriptionDocumentService = $container->get(MemberInscriptionDocumentService::class);
        $this->accessRightsService = $container->get(AccessRightsService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting member_inscription by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $memberInscription = $this->memberInscriptionService->getOne($args["id"]);

        return $response->withJson($this->addDocumentsToJsonMemberInscription($memberInscription), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getPageMemberInscriptions(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page memberInscriptions from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        if (isset($queryParams['hasPaid'])) {
            $value = $queryParams['hasPaid']; 
            $queryParams['hasPaid'] = filter_var(strtolower($value), FILTER_VALIDATE_BOOLEAN);
        }
        $params = new RequestParameters($queryParams, MemberInscription::getSearchFields(), MemberInscription::getFilterFields());

        $memberInscriptions = $this->memberInscriptionService->getPage($params);
        $memberInscriptionsWithDocument = array();
        foreach ($memberInscriptions as $memberInscription) {
            $memberInscriptionsWithDocument[] = $this->addDocumentsToJsonMemberInscription($memberInscription);
        }

        $count = $this->memberInscriptionService->getCount($params);

        $page = new Page($memberInscriptionsWithDocument, $params, $count);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function createMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating member_inscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $memberInscription = $this->memberInscriptionService->create($body);
        $this->entityManager->commit();

        return $response->withJson($this->addDocumentsToJsonMemberInscription($memberInscription), 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function updateMemberInscription(Request $request, Response $response, array $args)
    {
        $this->accessRightsService->checkRightsValidateOrModifyInscription($request);

        $this->logger->debug("Updating member_inscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $memberInscription = $this->memberInscriptionService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($this->addDocumentsToJsonMemberInscription($memberInscription), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws KerosException
     */
    public function deleteMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting member_inscription from " . $request->getServerParams()["REMOTE_ADDR"]);
        $this->accessRightsService->ensureOnlyGeneralSecretary($request);

        $this->entityManager->beginTransaction();
        $this->memberInscriptionService->delete($args['id']);
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
    public function validateMemberInscription(Request $request, Response $response, array $args)
    {
        $this->accessRightsService->checkRightsValidateOrModifyInscription($request);

        $this->logger->debug("Validating member_inscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberInscriptionService->validateMemberInscription($args['id']);
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
    public function confirmPaymentMemberInscription(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Confirming payment member_inscription from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberInscriptionService->confirmPaymentMemberInscription($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    /**
     * @param MemberInscription $memberInscription
     * @return array
     * @throws KerosException
     */
    private function addDocumentsToJsonMemberInscription(MemberInscription $memberInscription)
    {
        $memberInscriptionWithDocument = array();
        foreach ($memberInscription->jsonSerialize() as $key => $value) {
            $memberInscriptionWithDocument[$key] = $value;
        }
        $memberInscriptionWithDocument['documents'] = array();
        foreach ($this->memberInscriptionDocumentTypeService->getAll() as $memberInscriptionDocumentType) {
            $memberInscriptionWithDocument['documents'][] = array(
                'id' => $memberInscriptionDocumentType->getId(),
                'name' => $memberInscriptionDocumentType->getName(),
                'isTemplatable' => $memberInscriptionDocumentType->getisTemplatable(),
                'isUploaded' => $this->memberInscriptionDocumentService->documentTypeIsUploadedForMemberInscription($memberInscriptionDocumentType->getId(), $memberInscription->getId())
            );
        }
        return $memberInscriptionWithDocument;
    }
}