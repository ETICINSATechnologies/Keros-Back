<?php

namespace Keros\Controllers\Core;

use Doctrine\ORM\EntityManager;
use Keros\Error\KerosException;
use Keros\Entities\core\Member;
use Keros\Entities\Core\RequestParameters;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\MemberPositionService;
use Keros\Services\Auth\AccessRightsService;
use Keros\Tools\Authorization\JwtCodec;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Tools\ConfigLoader;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MemberController
{
    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var JwtCodec
     */
    private $jwtCodec;
    /**
     * @var ConfigLoader
     */
    private $kerosConfig;
    /**
     * @var MemberPositionService
     */
    private $memberPositionService;
    /**
     * @var AccessRightsService
     */
    private $accessRightsService;

    /**
     * MemberController constructor.
     * @param ContainerInterface $container
     */

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->entityManager = $container->get(EntityManager::class);
        $this->memberService = $container->get(MemberService::class);
        $this->memberPositionService = $container->get(MemberPositionService::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->accessRightsService = $container->get(AccessRightsService::class);

    }

    public function getMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting member by ID from " . $request->getServerParams()["REMOTE_ADDR"]);

        $member = $this->memberService->getOne($args["id"]);

        return $response->withJson($member, 200);
    }

    public function getConnectedUser(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting connected user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $member = $this->memberService->getOne($request->getAttribute("userId"));

        return $response->withJson($member, 200);
    }


    public function updateConnectedUser(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting updated user from " . $request->getServerParams()["REMOTE_ADDR"]);

        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $member = $this->memberService->update($request->getAttribute("userId"), $body);
        $this->entityManager->commit();

        return $response->withJson($member, 200);
    }

    public function getPageMembers(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Get page members from " . $request->getServerParams()["REMOTE_ADDR"]);
        $queryParams = $request->getQueryParams();
        $requestParameters = new RequestParameters($queryParams, Member::getSearchFields());

        $page = $this->memberService->getPage($requestParameters, $queryParams);

        return $response->withJson($page, 200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws KerosException
     */
    public function createMember(Request $request, Response $response, array $args)
    {
        //check access rights
        $this->accessRightsService->checkRightsCreateMember($request);

        $this->logger->debug("Creating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();
        $body = Validator::requiredArray($body);

        $this->entityManager->beginTransaction();
        $member = $this->memberService->create($body);
        $this->entityManager->commit();

        return $response->withJson($member, 201);
    }

    public function updateMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Updating member from " . $request->getServerParams()["REMOTE_ADDR"]);
        $body = $request->getParsedBody();

        $this->entityManager->beginTransaction();
        $member = $this->memberService->update($args['id'], $body);
        $this->entityManager->commit();
        
        return $response->withJson($member, 200);
    }

    public function deleteMember(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting member from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberService->delete($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    public function getLatestBoard(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting latest board from " . $request->getServerParams()["REMOTE_ADDR"]);

        $latestBoard = $this->memberService->getLatestBoard();

        return $response->withJson($latestBoard, 200);
    }

    public function createProfilePicture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Uploading profile picture for member " . $args['id'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);
        
        if ($request->getUploadedFiles() == null) {
            $msg = 'No file given';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        $uploadedFile = $request->getUploadedFiles()['file'];
        if ($uploadedFile == null) {
            $msg = 'File is empty';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $msg = "Error during file uploading";
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        if (!($extension=='jpg' || $extension=='jpeg' || $extension=='png')) {
            $msg = 'File format not supported, only jpg and png formats supported';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        $body = $args;
        $body['file'] = $uploadedFile->getClientFileName();
        
        $this->entityManager->beginTransaction();
        $filename = $this->memberService->createPhoto($args['id'], $body);
        $filepath = $this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . $filename;
        $uploadedFile->moveTo($filepath);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }

    public function getProfilePicture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Getting profile picture for member " . $args['id'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $filepath = $this->memberService->getPhoto($args["id"]);

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $mimeType = 'image/*';
        if ($extension=='jpg' || $extension=='jpeg') {
            $mimeType = 'image/jpeg';
        } else if ($extension=='png') {
            $mimeType = 'image/png';
        }

        $response   = $response->withHeader('Content-Type', $mimeType);
        $response   = $response->withHeader('Content-Disposition', 'attachment; filename="' .basename("$filepath") . '"');
        $response   = $response->withHeader('Content-Length', filesize($filepath));

        readfile($filepath);

        return $response;
    }

    public function deleteProfilePicture(Request $request, Response $response, array $args)
    {
        $this->logger->debug("Deleting profile picture for member " . $args['id'] . " from " . $request->getServerParams()["REMOTE_ADDR"]);

        $this->entityManager->beginTransaction();
        $this->memberService->deletePhoto($args['id']);
        $this->entityManager->commit();

        return $response->withStatus(204);
    }
}