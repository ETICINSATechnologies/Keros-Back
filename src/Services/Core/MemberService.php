<?php


namespace Keros\Services\Core;

use Keros\DataServices\Core\MemberDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class MemberService
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
     * @var UserService
     */
    private $userService;
    /**
     * @var DepartmentService
     */
    private $departmentService;
    /**
     * @var PositionService
     */
    private $positionService;
    /**
     * @var MemberDataService
     */
    private $memberDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->addressService = $container->get(AddressService::class);
        $this->genderService = $container->get(GenderService::class);
        $this->departmentService = $container->get(DepartmentService::class);
        $this->positionService = $container->get(PositionService::class);
        $this->userService = $container->get(UserService::class);
        $this->memberDataService = $container->get(MemberDataService::class);
    }

    /**
     * @param array $fields
     * @return Member
     * @throws KerosException
     */
    public function create(array $fields): Member
    {
        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::optionalPhone($fields["telephone"]);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear($fields["schoolYear"]);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $departmentId = Validator::requiredId($fields["departmentId"]);
        $department = $this->departmentService->getOne($departmentId);
        $positionIds = $fields["positionIds"];
        $positions = $this->positionService->getSome($positionIds);

        $member = new Member($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $gender, $department, $positions);

        $user = $this->userService->create($fields);
        $member->setUser($user);
        $address = $this->addressService->create($fields["address"]);
        $member->setAddress($address);
        $this->memberDataService->persist($member);

        return $member;
    }

    public function getOne(int $id): Member
    {
        $id = Validator::requiredId($id);

        $member = $this->memberDataService->getOne($id);
        if (!$member) {
            throw new KerosException("The member could not be found", 404);
        }
        return $member;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->memberDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->memberDataService->getCount($requestParameters);
    }

    public function update(int $id, ?array $fields): Member
    {
        $id = Validator::requiredId($id);
        $member = $this->getOne($id);

        if (isset($fields["firstName"])) {
            $firstName = Validator::requiredString($fields["firstName"]);
            $member->setFirstName($firstName);
        }
        if (isset($fields["lastName"])) {
            $lastName = Validator::requiredString($fields["lastName"]);
            $member->setLastName($lastName);
        }
        if (isset($fields["email"])) {
            $email = Validator::requiredEmail($fields["email"]);
            $member->setEmail($email);
        }
        if (isset($fields["telephone"])) {
            $telephone = Validator::requiredString($fields["telephone"]);
            $member->setTelephone($telephone);
        }
        if (isset($fields["birthday"])) {
            $birthday = Validator::requiredDate($fields["birthday"]);
            $member->setBirthday($birthday);
        }
        if (isset($fields["schoolYear"])) {
            $schoolYear = Validator::requiredSchoolYear($fields["schoolYear"]);
            $member->setSchoolYear($schoolYear);
        }
        if (isset($fields["genderId"])) {
            $genderId = Validator::requiredInt($fields["genderId"]);
            $gender = $this->genderService->getOne($genderId);
            $member->setGender($gender);
        }
        if (isset($fields["departmentId"])) {
            $departmentId = Validator::requiredInt($fields["departmentId"]);
            $department = $this->departmentService->getOne($departmentId);
            $member->setDepartment($department);
        }
        if (isset($fields["positionIds"])) {
            $positionIds = Validator::requiredArray($fields["positionIds"]);
            $positions = $this->positionService->getSome($positionIds);
            $member->setPositions($positions);
        }

        if (isset($fields["address"])) {
            $this->addressService->update($member->getAddress()->getId(), $fields["address"]);
        }
        $this->userService->update($member->getId(), $fields);
        $this->memberDataService->persist($member);

        return $member;
    }

}