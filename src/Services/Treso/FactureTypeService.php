<?php


namespace Keros\Services\Treso;

use Keros\DataServices\Treso\FactureTypeDataService;
use Keros\Entities\Treso\FactureType;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class FactureTypeService
{
    /**
     * @var FactureTypeDataService
     */
    private $factureTypeDataService;

    /**
     * FactureTypeService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->factureTypeDataService = $container->get(FactureTypeDataService::class);
    }

    /**
     * @param int $id
     * @return FactureType
     * @throws KerosException
     */
    public function getOne(int $id): FactureType
    {
        $id = Validator::requiredId($id);
        $factureType = $this->factureTypeDataService->getOne($id);
        if (!$factureType) {
            throw new KerosException("The factureType could not be found", 404);
        }
        return $factureType;
    }

    /**
     * @return string[]
     * @throws KerosException
     */
    public function getAll(): array
    {
        $factureTypes = $this->factureTypeDataService->getAll();
        $labels = array();
        foreach ($factureTypes as $facture){
            $labels[] = $facture->getLabel();
        }
        return $labels;
    }

    /**
     * @param string|null $label
     * @return FactureType
     * @throws KerosException
     */
    public function getFromLabel($label)
    {
        $factureTypes = $this->factureTypeDataService->getAll();
        foreach ($factureTypes as $facture){
            if($facture->getLabel() == $label)
                return $facture;
        }
        throw new KerosException("The factureType could not be found from string " . $label, 404);
    }
}