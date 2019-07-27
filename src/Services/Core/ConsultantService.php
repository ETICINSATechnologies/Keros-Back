<?php

namespace Keros\Services\Core;


use Keros\DataServices\Core\ConsultantDataService;
use Keros\DataServices\Core\TicketDataService;
use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class ConsultantService
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
     * @var TicketDataService
     */
    private $ticketDataService;
    /**
     * @var ConsultantDataService
     */
    private $consultantDataService;

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
        $this->userService = $container->get(UserService::class);
        $this->consultantDataService = $container->get(ConsultantDataService::class);
        $this->ticketDataService = $container->get(TicketDataService::class);
    }

    /**
     * @param array $fields
     * @return Consultant
     * @throws KerosException
     */
    public function create(array $fields): Consultant
    {
        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::requiredPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : null);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::requiredId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);

        $department = $this->departmentService->getOne($departmentId);

        $company = Validator::optionalString($fields["company"]);
        $profilePicture = Validator::optionalString($fields["profilePicture"]);
        $droitImage = Validator::requiredBool($fields['droitImage']);

        $consultant = new Consultant($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $gender, $department, $company, $profilePicture, $droitImage);

        $user = $this->userService->create($fields);
        $address = $this->addressService->create($fields["address"]);

        $consultant->setUser($user);
        $consultant->setAddress($address);

        $this->consultantDataService->persist($consultant);

        return $consultant;
    }

    public function getOne(int $id): Consultant
    {
        $id = Validator::requiredId($id);

        $consultant = $this->consultantDataService->getOne($id);
        if (!$consultant) {
            throw new KerosException("The consultant could not be found", 404);
        }
        return $consultant;
    }

    public function getPage(RequestParameters $requestParameters, array $queryParams): Page
    {
        if (isset($queryParams['year']) && $queryParams['year'] == 'latest') {
            $queryParams['year'] = $this->consultantPositionService->getLatestYear();
        }

        return $this->consultantDataService->getPage($requestParameters, $queryParams);
    }

    public function getSome(array $ids): array
    {
        $consultants = [];
        foreach ($ids as $id) {
            $id = Validator::requiredId($id);
            $consultant = $this->consultantDataService->getOne($id);
            if (!$consultant) {
                throw new KerosException("The consultant could not be found", 404);
            }
            $consultants[] = $consultant;
        }

        return $consultants;
    }

    public function update(int $id, ?array $fields): Consultant
    {
        $id = Validator::requiredId($id);
        $consultant = $this->getOne($id);

        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::requiredPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : null);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::requiredId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);

        $department = $this->departmentService->getOne($departmentId);

        $company = Validator::optionalString($fields["company"]);
        $profilePicture = Validator::optionalString($fields["profilePicture"]);

        $consultant->setFirstName($firstName);
        $consultant->setLastName($lastName);
        $consultant->setEmail($email);
        $consultant->setTelephone($telephone);
        $consultant->setBirthday($birthday);
        $consultant->setSchoolYear($schoolYear);
        $consultant->setGender($gender);
        $consultant->setDepartment($department);
        $consultant->setCompany($company);
        $consultant->setProfilePicture($profilePicture);

        $this->addressService->update($consultant->getAddress()->getId(), $fields["address"]);
        $this->userService->update($consultant->getId(), $fields);

        $this->consultantDataService->persist($consultant);

        return $consultant;
    }

    public function delete(int $id)
    {
        $id = Validator::requiredId($id);
        $consultant = $this->getOne($id);
        $address = $consultant->getAddress();

        $consultant->setStudiesAsConsultant([]);
        $this->consultantDataService->persist($consultant);

        $this->consultantDataService->delete($consultant);
        $this->userService->delete($id);
        $this->addressService->delete($address->getId());

    }

}