<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\StatusDataService;
use Keros\Entities\Ua\Status;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class StatusService
{
    /**
     * @var StatusDataService
     */
    private $statusDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->statusDataService = $container->get(StatusDataService::class);
    }

    public function getOne(int $id): Status
    {
        $id = Validator::requiredId($id);

        $status = $this->statusDataService->getOne($id);
        if (!$status) {
            throw new KerosException("The status could not be found", 404);
        }
        return $status;
    }

    public function getAll(): array
    {
        return $this->statusDataService->getAll();
    }


}