<?php

namespace Keros\Services\Auth;

use Keros\Services\Core\MemberPositionService;
use Keros\Tools\Validator;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Services\Core\MemberService;
use Keros\Services\Ua\StudyService;
use Keros\Entities\core\Member;
use Keros\Entities\UA\Study;
use Keros\Entities\core\MemberPosition;
use Keros\Error\KerosException;

class AccessRightsService
{
    /**
     * @var Member
     */
    private $currentMember;

    /**
     * @var MemberPosition[]
     */
    private $memberPositions;


    /**
     * AccessRights constructor.
     * @param Member $member
     */
    public function __construct(Member $member){
        $this->currentMember = $member;
        $this->memberPositions = $this->currentMember->getMemberPositions();
    }

    public function checkRightsPostMember() {
        $accessAllowed = array(19); //secrétaire général
        foreach($this->memberPositions as $memberPosition){
            if (!in_array($memberPosition->getPosition()->getId(),$accessAllowed)){
                throw new KerosException("You do not have the rights for creating a member", 404);
            }
        }
    }

    public function checkRightsConfidentialStudies(Study $study) {

        $accessAllowed = array(18, 17);
        foreach($this->memberPositions as $memberPosition){
            if ($memberPosition->getPosition()->getId() == 3){
                $leadersArray = $study->getLeadersArray();
                $isleader = false;
                foreach ($leadersArray as $leader){
                    $leader = Validator::requiredMember($leader);
                    if ($leader->getId() == $this->currentMember->getId()){
                        $isleader = true;
                    }
                }
                if ($isleader == false){
                    throw new KerosException("You do not have the rights for accessing this confidential study", 404);
                }
            }
            else if (!in_array($memberPosition->getPosition()->getId(),$accessAllowed)){
                throw new KerosException("You do not have the rights for accessing a confidential study", 404);
            }
        }
    }

    public function filterGetAllStudies(array $studies) : array {
        $accessAllowed = array(18,17); //resp UA et resp qualité
        foreach ($this->memberPositions as $memberPosition) {
            if (in_array($memberPosition->getPosition()->getId(), $accessAllowed)) {
                return $studies;
            }
        }

        $index = 0;
        foreach ($studies as $study) {
            $study = Validator::requiredStudy($study);
            if ($study->getConfidential() == true) {
                if (in_array(3, $this->memberPositions)) // si c'est le chargé d'affaires
                {
                    $leadersArray = $study->getLeadersArray();
                    $isLeader = false;
                    foreach ($leadersArray as $leader)
                    {
                        $leader = Validator::requiredMember($leader);
                        if ($leader->getId() == $this->currentMember->getId()) {
                            $isLeader = true;
                        }
                    }
                    if ($isLeader == false) {
                        unset($studies[$index]);
                    }
                } else {
                    unset($studies[$index]);
                }
            }
            $index += 1;
        }
        $studies = array_values($studies);
        return $studies;
    }

    public function checkRightsUpdateStudy(Study $study) {

        $leadersArray = $study->getLeadersArray();
        $isleader = false;
        foreach ($leadersArray as $leader){
            $leader = Validator::requiredMember($leader);
            if ($leader->getId() == $this->currentMember->getId()){
                $isleader = true;
            }
        }
        if ($isleader == false){
            throw new KerosException("You cannot update a study if you did not create it", 404);
        }
    }

    public function checkRightsAttributeQualityManager(){
        $accessAllowed = array(17,18); //resp qualité et UA
        foreach($this->memberPositions as $memberPosition){
            if (!in_array($memberPosition->getPosition()->getId(),$accessAllowed)){
                throw new KerosException("You cannot attribute a quality manager", 404);
            }
        }
    }
}