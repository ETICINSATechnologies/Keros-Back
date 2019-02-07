<?php

namespace Keros\Services\Core;


use Keros\DataServices\Core\MemberPositionDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\MemberPosition;
use Keros\DataServices\Core\MemberDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Doctrine\ORM\EntityRepository;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberPositionService
{
    /**
     * @var PositionService
     */
    private $positionService;
    /**
     * @var MemberDataService
     */
    private $memberDataService;
    /**
     * @var MemberPositionDataService
     */
    private $memberPositionDataService;
    /**
     * @var Logger
     */
    private $logger;


    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->positionService = $container->get(PositionService::class);
        $this->memberDataService = $container->get(MemberDataService::class);
        $this->memberPositionDataService = $container->get(MemberPositionDataService::class);
    }

    /**
     * @param Member $member
     * @param array $fields
     * @return MemberPosition
     * @throws KerosException
     */
    public function create(Member $member, array $fields): MemberPosition
    {
        $positionId = Validator::requiredId($fields["id"]);
        $isBoard = Validator::requiredBool($fields["isBoard"]);
        $year = Validator::requiredInt($fields["year"]);

        $position = $this->positionService->getOne($positionId);

        $memberPosition = new MemberPosition($member, $position, $isBoard, $year);
        $this->logger->debug(json_encode($memberPosition));

        $this->memberPositionDataService->persist($memberPosition);

        return $memberPosition;
    }

    public function getAll(): array
    {
        return $this->memberPositionDataService->getAll();
    }

    public function getLatestBoard(): array
    {
        $memberPositions = $this->memberPositionDataService->getAll();
        $boardMembers = array();
        $currentYear = 0;

        foreach ($memberPositions as $membersPosition) {
            if ($membersPosition->getYear() > $currentYear) {
                $currentYear = $membersPosition->getYear();
            }
        }

        foreach ($memberPositions as $membersPosition) {
            if ($membersPosition->getYear() == $currentYear and $membersPosition->getIsBoard() == true) {
                $boardMembers[] = $membersPosition;
            }
        }
        return $boardMembers;
    }

    /**
     * @param RequestParameters $requestParameters
     * @return MemberPosition[]
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->memberPositionDataService->getPage($requestParameters);
    }

    public function getSome(array $ids): array
    {
        $memberPositions = [];
        foreach ($ids as $id) {
            $this->logger->debug(is_int($id));
            $id = Validator::requiredId($id);
            $memberPosition = $this->memberPositionDataService->getOne($id);
            if (!$memberPosition) {
                throw new KerosException("The member position could not be found", 404);
            }
            $memberPositions[] = $memberPosition;
        }

        return $memberPositions;
    }

    public function delete(MemberPosition $memberPosition)
    {
        $this->memberPositionDataService->delete($memberPosition);
    }
}