<?php


namespace Keros\Services\Ua;

use Keros\DataServices\Ua\ProvenanceDataService;
use Keros\Entities\Ua\Provenance;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class ProvenanceService
{
    /**
     * @var ProvenanceDataService
     */
    private $provenanceDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->provenanceDataService = $container->get(ProvenanceDataService::class);
    }

    public function getOne(int $id): Provenance
    {
        $id = Validator::requiredId($id);

        $provenance = $this->provenanceDataService->getOne($id);
        if (!$provenance) {
            throw new KerosException("The provenance could not be found", 404);
        }
        return $provenance;
    }

    public function getAll(): array
    {
        return $this->provenanceDataService->getAll();
    }


}