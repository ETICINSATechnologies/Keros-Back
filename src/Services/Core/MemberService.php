<?php

namespace Keros\Services\Core;

use Keros\DataServices\Core\MemberDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Monolog\Logger;
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
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
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
        $telephone = Validator::optionalPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::optionalSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : null);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::optionalId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);
        if (isset($departmentId)) {
            $department = $this->departmentService->getOne($departmentId);
        }
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

    public function getSome(array $ids): array
    {
        $members = [];
        foreach ($ids as $id) {
            $id = Validator::requiredId($id);
            $member = $this->memberDataService->getOne($id);
            if (!$member) {
                throw new KerosException("The member could not be found", 404);
            }
            $members[] = $member;
        }

        return $members;
    }

    public function update(int $id, ?array $fields): Member
    {
        $id = Validator::requiredId($id);
        $member = $this->getOne($id);

        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::optionalPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::optionalSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : null);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::optionalId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);
        if (isset($departmentId)) {
            $department = $this->departmentService->getOne($departmentId);
        }
        $positionIds = $fields["positionIds"];
        $positions = $this->positionService->getSome($positionIds);

        $member->setFirstName($firstName);
        $member->setLastName($lastName);
        $member->setEmail($email);
        $member->setTelephone($telephone);
        $member->setBirthday($birthday);
        $member->setSchoolYear($schoolYear);
        $member->setGender($gender);
        $member->setDepartment($department);
        $member->setPositions($positions);

        $this->addressService->update($member->getAddress()->getId(), $fields["address"]);

        $this->userService->update($member->getId(), $fields);
        $this->memberDataService->persist($member);

        return $member;
    }

}