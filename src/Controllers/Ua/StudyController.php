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
use Keros\Services\Ua\StudyDocumentTypeService;
use Keros\Services\Ua\StudyService;
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
     * @var ConsultantService
     */
    private $consultantService;

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
        $this->kerosConfig = ConfigLoader::getConfig();
    }

    public function getStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting study by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $study = $this->studyService->getOne($args["id"]);

        return $response->withJson($study, 200);
    }

    public function getAllStudies(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get studies " . $request->getServerParams()["REMOTE_ADDR"]);
        $studies = $this->studyService->getAll();

        return $response->withJson($studies, 200);
    }

    public function getPageStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page studies from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $params = new RequestParameters($queryParams, Study::getSearchFields());

        $study = $this->studyService->getPage($params);
        $totalCount = $this->studyService->getCount($params);

        $page = new Page($study, $params, $totalCount);

        return $response->withJson($page, 200);
    }

    public function getCurrentUserStudies(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Searching for studies related to current user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $studies = [];
        $userId = $request->getAttribute("userId");

        //if the current user is a member
        try{
            $member = $this->memberService->getOne($userId);
            if (!empty($member->getStudiesAsLeader())) {
                $studies = array_unique(array_merge($studies, $member->getStudiesAsLeader()), SORT_REGULAR);
            }
            if (!empty($member->getStudiesAsQualityManager())) {
                $studies = array_unique(array_merge($studies, $member->getStudiesAsQualityManager()), SORT_REGULAR);
            }
        }catch(Exception $e){
            //if the current user is a consultant
            $consultant = $this->consultantService->getOne($userId);
            if ($consultant->getId() == $userId) {
                $consultant = $this->consultantService->getOne($userId);
                if (!empty($consultant->getStudiesAsConsultant())) {
                    $studies = array_unique(array_merge($studies, $consultant->getStudiesAsConsultant()), SORT_REGULAR);
                }
            }
        }


        /*
        foreach ($members as $member) {
            if ($member->getId() == $userId) {
                if (!empty($member->getStudiesAsLeader())) {
                    $studies = array_unique(array_merge($studies, $member->getStudiesAsLeader()), SORT_REGULAR);
                }
                if (!empty($member->getStudiesAsQualityManager())) {
                    $studies = array_unique(array_merge($studies, $member->getStudiesAsQualityManager()), SORT_REGULAR);
                }
                break;
            }
        }

        //if the current user is a consultant
        foreach ($consultants as $consultant) {
            if ($consultant->getId() == $userId) {
                $consultant = $this->consultantService->getOne($userId);
                if (!empty($consultant->getStudiesAsConsultant())) {
                    $studies = array_unique(array_merge($studies, $consultant->getStudiesAsConsultant()), SORT_REGULAR);
                }
                break;
            }
        }*/

        return $response->withJson($studies, 200);
    }

    public function createStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Creating study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $this->entityManager->beginTransaction();
        $study = $this->studyService->create($body);
        $this->entityManager->commit();

        return $response->withJson($study, 201);
    }

    public function deleteStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $this->entityManager->beginTransaction();
        $this->studyService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    public function updateStudy(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating study from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $study = $this->studyService->update($args['id'], $body);
        $this->entityManager->commit();

        return $response->withJson($study, 200);
    }

    public function getProvenance(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting provenance by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $provenance = $this->provenanceService->getOne($args["id"]);

        return $response->withJson($provenance, 200);
    }

    public function getAllProvenances(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get provenances " . $request->getServerParams()["REMOTE_ADDR"]);

        $provenances = $this->provenanceService->getAll();

        return $response->withJson($provenances, 200);
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
        $this->logger->debug("Get all documents for study " . $args["id"] . " " . $request->getServerParams()["REMOTE_ADDR"]);

        if (!$this->studyService->consultantsAreValid($args["id"]))
            throw new KerosException("Invalid consultant in study", 400);

        $documentTypes = array();
        foreach ($this->studyDocumentTypeService->getAll() as $studyDocumentType) {
            $documentTypes[] = array('id' => $studyDocumentType->getId(),
                'name' => pathinfo($studyDocumentType->getLocation(), PATHINFO_BASENAME),
                'generateLocation' => $this->kerosConfig["BACK_URL"] . "/api/v1/ua/study/" . $args["id"] . "/template/" . $studyDocumentType->getId(),
                'uploadLocation' => $this->kerosConfig["BACK_URL"] . "/api/v1/ua/study/" . $args["id"] . "/document/" . $studyDocumentType->getId(),
                'downloadLocation' => $this->kerosConfig["BACK_URL"] . "/api/v1/ua/study/" . $args["id"] . "/document/" . $studyDocumentType->getId());
        }

        return $response->withJson(array('documents' => $documentTypes), 200);
    }

}