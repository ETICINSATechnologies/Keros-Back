<?php

namespace Keros\Services\Auth;

use Keros\Services\Core\MemberPositionService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Keros\Services\Core\MemberService;
use Keros\Entities\core\Member;
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

    public function checkPostMember() {
        foreach($this->memberPositions as $memberPosition){
            if ($memberPosition->getPosition()->getId() != 19){
                throw new KerosException("You do not have the rights for creating a member", 404);
            }
        }
    }
}