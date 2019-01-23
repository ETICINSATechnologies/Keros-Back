<?php

namespace Keros\Services\Core;


use Keros\DataServices\Core\MemberPositionDataService;
use Keros\Entities\Core\MemberPosition;
use Keros\DataServices\Core\MemberDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
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
    }

    /**
     * @param array $fields
     * @return MemberPosition
     * @throws KerosException
     */
    public function create(array $fields): MemberPosition
    {
        $memberId = Validator::requiredId($fields["memberId"]);
        $positionId = Validator::requiredId($fields["positionId"]);
        $isBoard = Validator::requiredBool($fields["isBoard"]);
        $year = Validator::requiredSchoolYear($fields["year"]);

        $memberPosition = new MemberPosition($memberId,$positionId,$isBoard,$year);

        $this->memberPositionDataService->persist($memberPosition);

        return $memberPosition;
    }

    public function getAll(): array
    {
        return $this->memberPositionDataService->getAll();
    }


}