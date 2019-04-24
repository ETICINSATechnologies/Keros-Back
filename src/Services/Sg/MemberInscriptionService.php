<?php


namespace Keros\Services\Sg;

use Keros\DataServices\Sg\MemberInscriptionDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\CountryService;
use Keros\Services\Core\DepartmentService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\PoleService;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class   MemberInscriptionService
{
    /**
     * @var AddressService
     */
    private $addressService;

    /**
     * @var GenderService
     */
    private $genderService;

    /**
     * @var MemberService
     */
    private $memberService;

    /**
     * @var MemberInscriptionDataService
     */
    private $memberInscriptionDataService;

    /**
     * @var CountryService
     */
    private $countryService;

    /**
     * @var DepartmentService
     */
    private $departmentService;

    /**
     * @var PoleService
     */
    private $poleService;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->addressService = $container->get(AddressService::class);
        $this->genderService = $container->get(GenderService::class);
        $this->countryService = $container->get(CountryService::class);
        $this->departmentService = $container->get(DepartmentService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->poleService = $container->get(PoleService::class);
        $this->memberInscriptionDataService = $container->get(MemberInscriptionDataService::class);
    }

    /**
     * @param array $fields
     * @return   MemberInscription
     * @throws KerosException
     */
    public function create(array $fields): MemberInscription
    {
        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $departmentId = Validator::requiredId($fields["departmentId"]);
        $department = $this->departmentService->getOne($departmentId);
        $email = Validator::requiredEmail($fields["email"]);
        $phoneNumber = Validator::optionalPhone(isset($fields["phoneNumber"]) ? $fields["phoneNumber"] : null);
        $outYear = Validator::optionalInt(isset($fields["outYear"]) ? $fields["outYear"] : null);
        $nationalityId = Validator::requiredId($fields["nationalityId"]);
        $nationality = $this->countryService->getOne($nationalityId);
        $wantedPoleId = Validator::requiredId($fields["wantedPoleId"]);
        $wantedPole = $this->poleService->getOne($wantedPoleId);
        $address = $this->addressService->create($fields["address"]);

        $memberInscription = new MemberInscription($firstName, $lastName, $department, $email, $phoneNumber, $outYear, $nationality, $address, $wantedPole);

        $this->memberInscriptionDataService->persist($memberInscription);

        return $memberInscription;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id): void
    {
        $id = Validator::requiredId($id);
        $memberInscription = $this->getOne($id);
        $this->memberInscriptionDataService->delete($memberInscription);
    }

    /**
     * @param int $id
     * @return MemberInscription
     * @throws KerosException
     */
    public function getOne(int $id): MemberInscription
    {
        $id = Validator::requiredId($id);

        $memberInscription = $this->memberInscriptionDataService->getOne($id);
        if (!$memberInscription) {
            throw new KerosException("The memberInscription could not be found", 404);
        }
        return $memberInscription;
    }

    /**
     * @return array
     * @throws KerosException
     */
    public function getAll(): array
    {
        return $this->memberInscriptionDataService->getAll();
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->memberInscriptionDataService->getPage($requestParameters);
    }

    /**
     * @param RequestParameters $requestParameters
     * @return int
     */
    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->memberInscriptionDataService->getCount($requestParameters);
    }

    /**
     * @param int $id
     * @param array|null $fields
     * @return MemberInscription
     * @throws KerosException
     */
    public function update(int $id, ?array $fields): MemberInscription
    {
        $id = Validator::requiredId($id);
        $memberInscription = $this->getOne($id);

        $firstName = Validator::optionalString(isset($fields["firstName"]) ? $fields["firstName"] : null);
        $lastName = Validator::optionalString(isset($fields["lastName"]) ? $fields["lastName"] : null);
        $departmentId = Validator::optionalId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);
        $department = $this->departmentService->getOne($departmentId);
        $email = Validator::optionalEmail(isset($fields["email"]) ? $fields["email"] : null);
        $phoneNumber = Validator::optionalPhone(isset($fields["phoneNumber"]) ? $fields["phoneNumber"] : null);
        $outYear = Validator::optionalInt(isset($fields["outYear"]) ? $fields["outYear"] : null);
        $nationalityId = Validator::optionalId(isset($fields["nationalityId"]) ? $fields["nationalityId"] : null);
        $nationality = $this->countryService->getOne($nationalityId);
        $wantedPoleId = Validator::optionalId(isset($fields["wantedPoleId"]) ? $fields["wantedPoleId"] : null);
        $wantedPole = $this->poleService->getOne($wantedPoleId);

        $memberInscription->setFirstName($firstName);
        $memberInscription->setLastName($lastName);
        $memberInscription->setDepartment($department);
        $memberInscription->setEmail($email);
        $memberInscription->setPhoneNumber($phoneNumber);
        $memberInscription->setOutYear($outYear);
        $memberInscription->setNationality($nationality);
        $memberInscription->setWantedPole($wantedPole);
        $this->addressService->update($memberInscription->getAddress()->getId(), $fields["address"]);

        $this->memberInscriptionDataService->persist($memberInscription);

        return $memberInscription;
    }


    public function validateMemberInscription(int $id)
    {
        $id = Validator::requiredId($id);
        $memberInscription = $this->getOne($id);

        $memberArray = array(
            "firstName" => $memberInscription->getFirstName(),
            "lastName" => $memberInscription->getLastName(),
            "email" => $memberInscription->getEmail(),
            "telephone" => $memberInscription->getPhoneNumber(),
            "birthdate" => ,
            "schoolYear" => ,
            "genderId" => ,
            "departementId" => $memberInscription->getDepartment()->getId(),
            "address" => array(
                "line1" => $memberInscription->getAddress()->getLine1(),
                "line2" => $memberInscription->getAddress()->getLine2(),
                "postalCode" => $memberInscription->getAddress()->getPostalCode(),
                "city" => $memberInscription->getAddress()->getCity(),
                "countryId" => $memberInscription->getAddress()->getCountry()->getId()
            ),
        );

        $this->memberService->create($memberArray);
    }
}