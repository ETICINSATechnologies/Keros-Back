<?php

namespace Keros\Services\Core;


use Cassandra\Date;
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
        $isBoard = Validator::optionalBool($fields["isBoard"] ?? false);
        $year = Validator::optionalInt($fields["year"] ?? intval(date("Y")));

        $position = $this->positionService->getOne($positionId);

        $memberPosition = new MemberPosition($member, $position, $isBoard, $year);
        $this->logger->debug(json_encode($memberPosition));

        $this->memberPositionDataService->persist($memberPosition);

        return $memberPosition;
    }

    /**
     * @return MemberPosition[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->memberPositionDataService->getAll();
    }

    public function getLatestYear(): int {
        $memberPositions = $this->memberPositionDataService->getAll();
        $year = 0;
        foreach ($memberPositions as $membersPosition) {
            if ($membersPosition->getYear() > $year) {
                $year = $membersPosition->getYear();
            }
        }

        return $year;
    }

    public function getLatestBoard(): array
    {
        $memberPositions = $this->memberPositionDataService->getAll();
        $boardMembers = array();
        $currentYear = $this->getLatestYear();

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