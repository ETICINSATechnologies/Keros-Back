<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\PoleDataService;
use Keros\Entities\Core\Pole;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class PoleService
{
    /**
     * @var PoleDataService
     */
    private $poleDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->poleDataService = $container->get(PoleDataService::class);
    }

    public function getOne(int $id): Pole
    {
        $id = Validator::requiredId($id);
        $pole = $this->poleDataService->getOne($id);
        if (!$pole) {
            throw new KerosException("The pole could not be found", 404);
        }
        return $pole;
    }

    public function getAll(): array
    {
        return $this->poleDataService->getAll();
    }
}