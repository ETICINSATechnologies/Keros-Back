<?php

namespace Keros\Services\Auth;

use Keros\Services\Core\MemberService;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Entities\UA\Study;
use Keros\Error\KerosException;
use Psr\Http\Message\ServerRequestInterface as Request;

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
    public function checkRightsPostMember(Request $request)
    {
        $accessAllowed = array(19, 22); //resp RH et secrétaire général

        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();
        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }
        throw new KerosException("You do not have the rights for creating a member", 401);
    }

    public function checkRightsConfidentialStudies(Request $request, Study $study)
    {
        $accessAllowed = array(21); //resp UA
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();

        foreach ($memberPositions as $memberPosition) {
            if ($memberPosition->getPosition()->getId() == 3) { // si chargé d'affaire
                $leadersArray = $study->getLeadersArray();
                foreach ($leadersArray as $leader) {
                    if ($leader->getId() == $currentMember->getId()) { // si il est en charge de cette étude
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
        $accessAllowed = array(21, 18); //resp UA et resp qualité
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
        $accessAllowed = array(21); //resp UA
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
        $accessAllowed = array(18, 21); //resp qualité et UA
        $currentMember = $this->memberService->getOne($request->getAttribute("userId"));
        $memberPositions = $currentMember->getMemberPositions();

        foreach ($memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return;
            }
        }
        throw new KerosException("You cannot attribute a quality manager", 401);
    }
}