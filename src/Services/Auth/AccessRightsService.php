<?php

namespace Keros\Services\Auth;

use Keros\Services\Core\MemberService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Entities\UA\Study;
use Keros\Error\KerosException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AccessRightsService
{

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var MemberService
     */
    protected $memberService;

    public const HR_MANAGER_ID = 19;
    public const GENERAL_SECRETARY_ID = 22;
    public const BUSINESS_MANAGER_ID = 3; //charge d'affaires
    public const UA_MANAGER_ID = 21;
    public const QUALITY_MANAGER_ID = 18;

    /**
     * AccessRightsService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->memberService = $container->get(MemberService::class);
        $this->logger = $container->get(Logger::class);
    }

    /**
     * @param Request $request
     * @throws KerosException
     */
    public function checkRightsCreateMember(Request $request)
    {
        $accessAllowed = array(self::HR_MANAGER_ID, self::GENERAL_SECRETARY_ID);

        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();
        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }
        throw new KerosException("You do not have the rights for creating a member", 401);
    }

    /**
     * return if member that send the request have one of the positions specified in @param $positionsIdAllowed.
     * Throws exception otherwise
     * @param Request $request
     * @param mixed ...$positionsIdAllowed
     * @throws KerosException
     */
    public function ensureMemberIs(Request $request, ... $positionsIdAllowed){

        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();
        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $positionsIdAllowed)) {
                return;
            }
        }
        throw new KerosException("You do not have the rights for creating a member", 401);
    }

    public function checkRightsConfidentialStudies(Request $request, Study $study)
    {
        $accessAllowed = array(self::UA_MANAGER_ID);
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();

        foreach ($memberPositions as $memberPosition) {
            if ($memberPosition->getPosition()->getId() == self::BUSINESS_MANAGER_ID) {
                $leadersArray = $study->getLeadersArray();
                foreach ($leadersArray as $leader) {
                    if ($leader->getId() == $currentMember->getId()) { //if the user is the business manager of this study
                        return;
                    }
                }
            } else if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }
        throw new KerosException("You do not have the rights for accessing a confidential study", 401);
    }

    /**
     * @param Request $request
     * @param Study[] $studies
     * @return array
     * @throws KerosException
     */
    public function filterGetAllStudies(Request $request, array $studies): array
    {
        $accessAllowed = array(self::UA_MANAGER_ID, self::QUALITY_MANAGER_ID);
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositionsArray();

        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return $studies;
            }
        }

        $index = 0;
        foreach ($studies as $study) {
            if ($study->getConfidential() == true) {
                $isLeader = false;
                foreach ($study->getLeadersArray() as $leader) {
                    if ($leader->getId() == $currentMember->getId()) {
                        $isLeader = true;
                    }
                }
                if ($isLeader == false) {
                    unset($studies[$index]);
                }
            }
            $index += 1;
        }

        $studies = array_values($studies);
        return $studies;
    }

    public function checkRightsUpdateStudy(Request $request, Study $study)
    {
        $accessAllowed = array(self::UA_MANAGER_ID);
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositionsArray();

        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }

        $leadersArray = $study->getLeadersArray();
        $isLeader = false;
        foreach ($leadersArray as $leader) {
            if ($leader->getId() == $currentMember->getId()) {
                $isLeader = true;
            }
        }
        if ($isLeader == false) {
            throw new KerosException("You cannot update a study if you are not a leader of this study", 401);
        }
    }

    public function checkRightsAttributeQualityManager(Request $request)
    {
        $accessAllowed = array(self::QUALITY_MANAGER_ID, self::UA_MANAGER_ID);
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();

        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }
        throw new KerosException("You cannot attribute a quality manager", 401);
    }

    /**
     * @param Request $request
     * @throws KerosException
     */
    public function checkRightsValidateOrModifyInscription(Request $request)
    {
        $accessAllowed = array(self::GENERAL_SECRETARY_ID);
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();

        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }
        throw new KerosException("You cannot validate or update inscriptions", 401);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @throws KerosException 
     * @return Response
     */
    public function checkRightsNotAlumni(Request $request, Response $response, callable $next)
    {
        $isAlumni = false;

        try {
            $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
            $isAlumni = $currentMember->getIsAlumni();            
        } catch (KerosException $e) {
            //un consultant
        }

        if($isAlumni){
            throw new KerosException("You cannot access this route", 401);
        }

        $response = $next($request, $response);
        return $response;
    }

    public function ensureOnlyGeneralSecretary(Request $request){
        $this->ensureMemberIs($request, 22);
    }
}