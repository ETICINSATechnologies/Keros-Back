<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\CountryDataService;
use Keros\DataServices\Core\GenderDataService;
use Keros\Entities\Core\Country;
use Keros\Entities\Core\Gender;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class GenderService
{
    /**
     * @var GenderDataService
     */
    private $genderDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->genderDataService = $container->get(GenderDataService::class);
    }

    public function getOne(int $id): Gender
    {
        $id = Validator::requiredId($id);
        $gender = $this->genderDataService->getOne($id);
        if (!$gender) {
            throw new KerosException("The gender could not be found", 404);
        }
        return $gender;
    }

    public function getAll(): array
    {
        return $this->genderDataService->getAll();
    }
}