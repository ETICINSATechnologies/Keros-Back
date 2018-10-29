<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\PositionDataService;
use Keros\Entities\Core\Position;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class PositionService
{
    /**
     * @var PositionDataService
     */
    private $positionDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->positionDataService = $container->get(PositionDataService::class);
    }

    public function getOne(int $id): Position
    {
        $id = Validator::requiredId($id);
        $position = $this->positionDataService->getOne($id);
        if (!$position) {
            throw new KerosException("The position could not be found", 404);
        }
        return $position;
    }

    public function getSome(array $ids): array
    {
        $positions = [];
        foreach ($ids as $id) {
            $id = Validator::requiredId($id);
            $position = $this->positionDataService->getOne($id);
            if (!$position) {
                throw new KerosException("The position could not be found", 404);
            }
            $positions[] = $position;
        }

        return $positions;
    }

    public function getAll(): array
    {
        return $this->positionDataService->getAll();
    }
}