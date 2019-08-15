<?php

namespace Keros\Services\Core;

use Exception;
use Keros\DataServices\Core\MemberDataService;
use Keros\DataServices\Core\TicketDataService;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberService
{
    /** @var AddressService */
    private $addressService;

    /** @var GenderService */
    private $genderService;

    /** @var UserService */
    private $userService;

    /** @var DepartmentService */
    private $departmentService;

    /** @var TicketDataService */
    private $ticketDataService;

    /** @var MemberDataService */
    private $memberDataService;

    /** @var MemberPositionService */
    private $memberPositionService;

    /** @var ConfigLoader */
    private $kerosConfig;

    /** @var DirectoryManager */
    private $directoryManager;

    /** @var Logger */
    private $logger;

    /**
     * MemberService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->addressService = $container->get(AddressService::class);
        $this->memberPositionService = $container->get(MemberPositionService::class);
        $this->genderService = $container->get(GenderService::class);
        $this->departmentService = $container->get(DepartmentService::class);
        $this->userService = $container->get(UserService::class);
        $this->memberDataService = $container->get(MemberDataService::class);
        $this->ticketDataService = $container->get(TicketDataService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
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

        $company = Validator::optionalString($fields["company"]);
        $profilePicture = null;
        $droitImage = Validator::requiredBool($fields['droitImage']);

        $member = new Member($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $gender, $department, $company, $profilePicture, $droitImage, array());

        $user = $this->userService->create($fields);
        $address = $this->addressService->create($fields["address"]);

        $member->setUser($user);
        $member->setAddress($address);

        $this->memberDataService->persist($member);

        $memberPositions = [];
        foreach ($fields["positions"] as $position) {
            $memberPositions[] = $this->memberPositionService->create($member, $position);
        }
        $member->setMemberPositions($memberPositions);

        return $member;
    }

    /**
     * @param int $id
     * @return Member
     * @throws KerosException
     */
    public function getOne(int $id): Member
    {
        $id = Validator::requiredId($id);

        $member = $this->memberDataService->getOne($id);
        if (!$member) {
            throw new KerosException("The member could not be found", 404);
        }
        return $member;
    }

    /**
     * @param RequestParameters $requestParameters
     * @param array $queryParams
     * @return Page
     * @throws KerosException
     */
    public function getPage(RequestParameters $requestParameters, array $queryParams): Page
    {
        if (isset($queryParams['year']) && $queryParams['year'] == 'latest') {
            $queryParams['year'] = $this->memberPositionService->getLatestYear();
        }

        return $this->memberDataService->getPage($requestParameters, $queryParams);
    }

    /**
     * @param array $ids
     * @return array
     * @throws KerosException
     */
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

    /**
     * @param int $id
     * @param array|null $fields
     * @return Member
     * @throws KerosException
     */
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

        $company = Validator::optionalString($fields["company"]);

        $memberPositions = $member->getMemberPositions();
        foreach ($memberPositions as $memberPosition)
            $this->memberPositionService->delete($memberPosition);

        $memberPositions = [];
        foreach ($fields["positions"] as $position) {
            $memberPositions[] = $this->memberPositionService->create($member, $position);
        }
        $member->setMemberPositions($memberPositions);

        $member->setFirstName($firstName);
        $member->setLastName($lastName);
        $member->setEmail($email);
        $member->setTelephone($telephone);
        $member->setBirthday($birthday);
        $member->setSchoolYear($schoolYear);
        $member->setGender($gender);
        $member->setDepartment($department);
        $member->setCompany($company);
        $member->setMemberPositions($memberPositions);

        $this->addressService->update($member->getAddress()->getId(), $fields["address"]);
        $this->userService->update($member->getId(), $fields);

        $this->memberDataService->persist($member);

        return $member;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function delete(int $id)
    {
        $id = Validator::requiredId($id);
        $member = $this->getOne($id);
        $address = $member->getAddress();
        $memberPositions = $member->getMemberPositions();
        foreach ($memberPositions as $memberPosition)
            $this->memberPositionService->delete($memberPosition);
        $member->setStudiesAsQualityManager([]);
        $member->setStudiesAsLeader([]);
        $this->memberDataService->persist($member);
        $this->ticketDataService->deleteTicketsRelatedToMember($id);
        $profilepicture = $member->getProfilePicture();
        $this->memberDataService->delete($member);
        $this->userService->delete($id);
        $this->addressService->delete($address->getId());
        $filepath = $this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . $profilepicture;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    /**
     * @return Member[]
     */
    public function getLatestBoard(): array
    {
        $boardMembersPositions = $this->memberPositionService->getLatestBoard();
        $boardMembers = array();

        foreach ($boardMembersPositions as $boardMemberPosition) {
            $memberId = $boardMemberPosition->getMember();
            $boardMembers[] = $memberId;
        }
        return $boardMembers;

    }

    /**
     * @param int $id
     * @param array $fields
     * @return Member
     * @throws Exception
     */
    public function createPhoto(int $id, ?array $fields): String
    {
        if ($fields['file'] == null) {
            $msg = 'File is empty in given parameters';
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }
        $file = $fields['file'];

        $id = Validator::requiredId($id);
        $member = $this->getOne($id);

        if (!$member) {
            throw new KerosException("The member could not be found", 404);
        }

        $filename = $member->getProfilePicture();

        if ($filename) {
            $filepath = $this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . $filename;
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }

        $filename = $this->directoryManager->uniqueFilename($file, false, $this->kerosConfig['MEMBER_PHOTO_DIRECTORY']);

        $this->directoryManager->mkdir($this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . pathinfo($filename, PATHINFO_DIRNAME));
        $member->setProfilePicture($filename);

        $this->memberDataService->persist($member);

        return $filename;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function deletePhoto(int $id): void
    {
        $id = Validator::requiredId($id);

        $member = $this->getOne($id);

        if (!$member) {
            throw new KerosException("The member could not be found", 404);
        }

        $filename = $member->getProfilePicture();

        if (!$filename) {
            throw new KerosException("Profile picture could not be found", 404);
        }

        $filepath = $this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . $filename;

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $member->setProfilePicture(null);
        $this->memberDataService->persist($member);
    }

    /**
     * @param int $id
     * @return String
     * @throws KerosException
     */
    public function getPhoto(int $id): String
    {
        $id = Validator::requiredId($id);

        $member = $this->getOne($id);

        if (!$member) {
            throw new KerosException("The member could not be found", 404);
        }

        $filename = $member->getProfilePicture();

        if (!$filename) {
            throw new KerosException("No profile picture for this member", 404);
        }

        $filepath = $this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . $filename;

        if (!file_exists($filepath)) {
            throw new KerosException("Profile picture could not be found", 404);
        }

        return $filepath;
    }

}