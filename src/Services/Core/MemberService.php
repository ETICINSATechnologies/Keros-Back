<?php

namespace Keros\Services\Core;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class MemberService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->entityManager = $container->get('entityManager');
        $this->repository = $this->entityManager->getRepository(Member::class);
    }

    /**
     * @param Member $member
     * @param int $userId
     * @param int $genderId
     * @param int $departmentId
     * @param $addressId
     * @throws KerosException
     */
    public function create(Member $member, int $userId, int $genderId, int $departmentId, $addressId)
    {
        $this->entityManager->beginTransaction();
        try {
            $user = $this->entityManager->getReference('Keros\Entities\Core\User', $userId);
            $gender = $this->entityManager->getReference('Keros\Entities\Core\Gender', $genderId);
            $department = $this->entityManager->getReference('Keros\Entities\Core\Department', $departmentId);
            $address = $this->entityManager->getReference('Keros\Entities\Core\Address', $addressId);

            $member->setUser($user);
            $member->setGender($gender);
            $member->setDepartment($department);
            $member->setAddress($address);

            $this->entityManager->persist($member);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $msg = "Failed to create new member : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param int $id
     * @return Member|null
     * @throws KerosException
     */
    public function getOne(int $id): ?Member
    {
        try {
            $member = $this->repository->find($id);
            return $member;
        } catch (Exception $e) {
            $msg = "Error finding member with ID $id : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param RequestParameters $requestParameters
     * @return array
     * @throws KerosException
     */
    public function getMany(RequestParameters $requestParameters): array
    {
        $criteria = $requestParameters->getCriteria();
        try {
            $members = $this->repository->matching($criteria)->getValues();
            return $members;
        } catch (Exception $e) {
            $msg = "Error finding page of members : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    public function getCount(?RequestParameters $requestParameters): int
    {
        if ($requestParameters != null) {
            $countCriteria = $requestParameters->getCountCriteria();
            $count = $this->repository->matching($countCriteria)->count();
        } else {
            $count = $this->repository->matching(Criteria::create())->count();
        }
        return $count;
    }

    /**
     * @param $memberId
     * @param $genderId
     * @param $departmentId
     * @param $firstName
     * @param $lastName
     * @param $birthday
     * @param $telephone
     * @param $email
     * @param $schoolYear
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     * @throws KerosException
     */
    public function update($memberId, $genderId, $departmentId, $firstName, $lastName, $birthday, $telephone, $email, $schoolYear)
    {
        $this->entityManager->beginTransaction();
        try {
            $gender = $this->entityManager->getReference('Keros\Entities\Core\Gender', $genderId);
            $department = $this->entityManager->getReference('Keros\Entities\Core\Department', $departmentId);
            $member = $this->entityManager->getReference('Keros\Entities\Core\Member', $memberId);

            $member->setGender($gender);
            $member->setDepartment($department);
            $member->setFirstName($firstName);
            $member->setLastName($lastName);
            $member->setBirthDate($birthday);
            $member->setTelephone($telephone);
            $member->setEmail($email);
            $member->setSchoolYear($schoolYear);

            $this->entityManager->persist($member);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $member;
        } catch (Exception $e) {
            $msg = "Failed to update member : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }

    /**
     * @param $memberId
     * @param $positionIds
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     * @throws KerosException
     */
    public function updatePosition($memberId, $positionIds)
    {
        $this->entityManager->beginTransaction();
        try {
            $member = $this->repository->find($memberId);
            $member->deleteAllPositions();

            foreach ($positionIds as $positionId)
            {
                $position = $this->entityManager->getReference('Keros\Entities\Core\Position', $positionId);
                $member->addPosition($position);
            }

            $this->entityManager->persist($member);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $member;
        } catch (Exception $e) {
            $msg = "Failed to update member position : " . $e->getMessage();
            $this->logger->error($msg);
            throw new KerosException($msg, 500);
        }
    }
}