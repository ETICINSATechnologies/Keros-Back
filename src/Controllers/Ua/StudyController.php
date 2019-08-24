<?php

namespace Keros\Controllers\Ua;

use Doctrine\ORM\EntityManager;
use Exception;
use Keros\Entities\Core\Page;
use Keros\Error\KerosException;
use Keros\Services\Core\ConsultantService;
use Keros\Services\Core\MemberService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Study;
use Keros\Services\Ua\FieldService;
use Keros\Services\Ua\ProvenanceService;
use Keros\Services\Ua\StatusService;
use Keros\Services\Ua\StudyDocumentService;
use Keros\Services\Ua\StudyDocumentTypeService;
use Keros\Services\Ua\StudyService;
use Keros\Services\Auth\AccessRightsService;
use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class StudyController
{
    /**
     * @var StudyService
     */
    private $studyService;

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
     * @var StudyDocumentTypeService
     */
    private $studyDocumentTypeService;

    /**
     * @var
     */
    private $kerosConfig;
    /**
     * @var AccessRightsService
     */
    private $accessRightsService;

    /**
     * @var ConsultantService
     */
    private $consultantService;

    /**
     * @var StudyDocumentService
     */
    private $studyDocumentService;

    /**
     * StudyController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->studyService = $container->get(StudyService::class);
        $this->provenanceService = $container->get(ProvenanceService::class);
        $this->fieldService = $container->get(FieldService::class);
        $this->statusService = $container->get(StatusService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->studyDocumentTypeService = $container->get(StudyDocumentTypeService::class);
        $this->consultantService = $container->get(ConsultantService::class);
        $this->accessRightsService = $container->get(AccessRightsService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->studyDocumentService = $container->get(StudyDocumentService::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting study by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $study = $this->studyService->getOne($args["id"]);

        if ($study->getConfidential() == true) {
            $this->accessRightsService->checkRightsConfidentialStudies($request, $study);
        }

        return $response->withJson($this->addDocumentsToJsonStudy($study), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getAllStudies(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get studies " . $request->getServerParams()["REMOTE_ADDR"]);
        $studies = $this->studyService->getAll();

        $studiesWithDocuments = array();
        foreach ($studies as $study)
            $studiesWithDocuments[] = $this->addDocumentsToJsonStudy($study);


        return $response->withJson($studiesWithDocuments, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getPageStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page studies from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Study::getSearchFields());

        $studies = $this->studyService->getPage($params);
        $studies = $this->accessRightsService->filterGetAllStudies($request, $studies);

        $studiesWithDocuments = array();
        foreach ($studies as $study)
            $studiesWithDocuments[] = $this->addDocumentsToJsonStudy($study);

        $totalCount = $this->studyService->getCount($params);

        $page = new Page($studiesWithDocuments, $params, $totalCount);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getCurrentUserStudies(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Searching for studies related to current user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $studies = [];
        $userId = $request->getAttribute("userId");

        //if the current user is a member
        try {
            $member = $this->memberService->getOne($userId);
            if (!empty($member->getStudiesAsLeader())) {
                $studies = array_unique(array_merge($studies, $member->getStudiesAsLeader()), SORT_REGULAR);
            }
            if (!empty($member->getStudiesAsQualityManager())) {
                $studies = array_unique(array_merge($studies, $member->getStudiesAsQualityManager()), SORT_REGULAR);
            }
        } catch (KerosException $e) {
            //if the current user is a consultant
            $consultant = $this->consultantService->getOne($userId);
            if ($consultant->getId() == $userId) {
                if (!empty($consultant->getStudiesAsConsultant())) {
                    $studies = array_unique(array_merge($studies, $consultant->getStudiesAsConsultant()), SORT_REGULAR);
                }
            }
        }

        $studiesWithDocuments = array();
        foreach ($studies as $study)
            $studiesWithDocuments[] = $this->addDocumentsToJsonStudy($study);

        return $response->withJson($studiesWithDocuments, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function createStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $this->entityManager->beginTransaction();
        $study = $this->studyService->create($body);
        $this->entityManager->commit();

        return $response->withJson($this->addDocumentsToJsonStudy($study), 201);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $this->entityManager->beginTransaction();
        $this->studyService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function updateStudy(Request $request, Response $response, array $args)
    {
        $study = $this->studyService->getOne($args['id']);
        $this->accessRightsService->checkRightsUpdateStudy($request, $study);

        $this->logger->debug("Updating study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $oldQualityManagers = $study->getQualityManagersArray();
        sort($oldQualityManagers);

        $study = $this->studyService->update($args['id'], $body);
        $newQualityManagers = $study->getQualityManagersArray();

        sort($newQualityManagers);
        if ($newQualityManagers != $oldQualityManagers) {
            $this->accessRightsService->checkRightsAttributeQualityManager($request);
        }

        $this->entityManager->commit();

        return $response->withJson($this->addDocumentsToJsonStudy($study), 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getProvenance(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting provenance by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $provenance = $this->provenanceService->getOne($args["id"]);

        return $response->withJson($provenance, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function getAllProvenances(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get provenances " . $request->getServerParams()["REMOTE_ADDR"]);

        $provenances = $this->provenanceService->getAll();

        return $response->withJson($provenances, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getField(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting field by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $field = $this->fieldService->getOne($args["id"]);

        return $response->withJson($field, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function getAllFields(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get fields " . $request->getServerParams()["REMOTE_ADDR"]);

        $fields = $this->fieldService->getAll();

        return $response->withJson($fields, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function getStatus(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting status by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $status = $this->statusService->getOne($args["id"]);

        return $response->withJson($status, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function getAllStatus(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get status " . $request->getServerParams()["REMOTE_ADDR"]);

        $status = $this->statusService->getAll();

        return $response->withJson($status, 200);
    }

    /**
     * @param Study $study
     * @return array
     * @throws KerosException
     */
    public function addDocumentsToJsonStudy(Study $study)
    {
        $studyWithDocuments = array();
        foreach ($study->jsonSerialize() as $key => $value) {
            $studyWithDocuments[$key] = $value;
        }

        $studyWithDocuments['documents'] = array();
        foreach ($this->studyDocumentTypeService->getAll() as $studyDocumentType) {
            $studyWithDocuments['documents'][] = array(
                "id" => $studyDocumentType->getId(),
                "name" => $studyDocumentType->getName(),
                "isTemplatable" => $studyDocumentType->getisTemplatable(),
                "isUploaded" => $this->studyDocumentService->documentTypeIsUploadedForStudy($studyDocumentType->getId(), $study->getId())
            );
        }
        $this->logger->info(json_encode($studyWithDocuments));

        return $studyWithDocuments;
    }

}