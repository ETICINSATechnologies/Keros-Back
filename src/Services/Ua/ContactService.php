<?php


namespace Keros\Services\Ua;

use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Ua\Contact;
use Keros\Error\KerosException;
use Keros\DataServices\Ua\ContactDataService;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\DepartmentService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\PositionService;
use Keros\Services\Core\UserService;
use Keros\Tools\Validator;
use Psr\Container\ContainerInterface;

class ContactService
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
     * @var FirmService
     */
    private $firmService;
    /**
     * @var PositionService
     */
    private $positionService;
    /**
     * @var ContactDataService
     */
    private $contactDataService;

    public function __construct(ContainerInterface $container)
    {
        $this->addressService = $container->get(AddressService::class);
        $this->genderService = $container->get(GenderService::class);
        $this->firmService = $container->get(FirmService::class);
        $this->positionService = $container->get(PositionService::class);
        $this->userService = $container->get(UserService::class);
        $this->contactDataService = $container->get(ContactDataService::class);
    }

    /**
     * @param array $fields
     * @return Contact
     * @throws KerosException
     */
    public function create(array $fields): Contact
    {
        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $telephone = Validator::optionalPhone($fields["telephone"]);
        $cellphone = Validator::optionalPhone($fields["cellphone"]);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $firmId = Validator::requiredId($fields["firmId"]);
        $firm = $this->firmService->getOne($firmId);

        $contact = new Contact($firstName, $lastName, $gender, $firm, $email, $telephone, $cellphone);

        if (isset($fields["position"])) {
            $position = Validator::requiredString($fields["position"]);
            $contact->setFirstName($position);
        }

        if (isset($fields["notes"])) {
            $notes = Validator::requiredString($fields["notes"]);
            $contact->setFirstName($notes);
        }

        if (isset($fields["old"])) {
            $old = Validator::requiredBool($fields["old"]);
            $contact->setFirstName($old);
        }

        $this->contactDataService->persist($contact);

        return $contact;
    }

    public function getOne(int $id): Contact
    {
        $id = Validator::requiredId($id);

        $contact = $this->contactDataService->getOne($id);
        if (!$contact) {
            throw new KerosException("The contact could not be found", 404);
        }
        return $contact;
    }

    public function getPage(RequestParameters $requestParameters): array
    {
        return $this->contactDataService->getPage($requestParameters);
    }

    public function getCount(RequestParameters $requestParameters): int
    {
        return $this->contactDataService->getCount($requestParameters);
    }

    public function update(int $id, ?array $fields): Contact
    {
        $id = Validator::requiredId($id);
        $contact = $this->getOne($id);

        if (isset($fields["firstName"])) {
            $firstName = Validator::requiredString($fields["firstName"]);
            $contact->setFirstName($firstName);
        }
        if (isset($fields["lastName"])) {
            $lastName = Validator::requiredString($fields["lastName"]);
            $contact->setLastName($lastName);
        }
        if (isset($fields["genderId"])) {
            $genderId = Validator::requiredId($fields["genderId"]);
            $gender = $this->genderService->getOne($genderId);
            $contact->setGender($gender);
        }
        if (isset($fields["firmId"])) {
            $firmId = Validator::requiredId($fields["firmId"]);
            $firm = $this->firmService->getOne($firmId);
            $contact->setFirm($firm);
        }
        if (isset($fields["email"])) {
            $email = Validator::requiredEmail($fields["email"]);
            $contact->setEmail($email);
        }
        if (isset($fields["telephone"])) {
            $telephone = Validator::requiredString($fields["telephone"]);
            $contact->setTelephone($telephone);
        }
        if (isset($fields["cellphone"])) {
            $cellphone = Validator::requiredString($fields["cellphone"]);
            $contact->setTelephone($cellphone);
        }
        if (isset($fields["position"])) {
            $position = Validator::requiredString($fields["position"]);
            $contact->setFirstName($position);
        }
        if (isset($fields["notes"])) {
            $notes = Validator::requiredString($fields["notes"]);
            $contact->setFirstName($notes);
        }
        if (isset($fields["old"])) {
            $old = Validator::requiredBool($fields["old"]);
            $contact->setFirstName($old);
        }

        $this->userService->update($contact->getId(), $fields);
        $this->contactDataService->persist($contact);

        return $contact;
    }

}