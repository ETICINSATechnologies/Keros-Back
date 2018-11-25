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
        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $firmId = Validator::requiredId($fields["firmId"]);
        $firm = $this->firmService->getOne($firmId);
        $telephone = Validator::optionalPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $cellphone = Validator::optionalPhone(isset($fields["cellphone"]) ? $fields["cellphone"] : null);
        $position = Validator::optionalString(isset($fields["position"]) ? $fields["position"] : null);
        $notes = Validator::optionalString(isset($fields["notes"]) ? $fields["notes"] : null);
        $old = Validator::optionalBool(isset($fields["old"]) ? $fields["old"] : false);

        $contact = new Contact($firstName, $lastName, $gender, $firm, $email, $old);
        $contact->setTelephone($telephone);
        $contact->setCellphone($cellphone);
        $contact->setPosition($position);
        $contact->setNotes($notes);

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

    public function getSome(array $ids): array
    {
        $contacts = [];
        foreach ($ids as $id) {
            $id = Validator::requiredId($id);
            $contact = $this->contactDataService->getOne($id);
            if (!$contact) {
                throw new KerosException("The contact could not be found", 404);
            }
            $contacts[] = $contact;
        }

        return $contacts;
    }

    public function update(int $id, ?array $fields): Contact
    {
        $id = Validator::requiredId($id);
        $contact = $this->getOne($id);

        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $email = Validator::requiredEmail($fields["email"]);
        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $firmId = Validator::requiredId($fields["firmId"]);
        $firm = $this->firmService->getOne($firmId);
        $telephone = Validator::optionalPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $cellphone = Validator::optionalPhone(isset($fields["cellphone"]) ? $fields["cellphone"] : null);
        $position = Validator::optionalString(isset($fields["position"]) ? $fields["position"] : null);
        $notes = Validator::optionalString(isset($fields["notes"]) ? $fields["notes"] : null);
        $old = Validator::optionalBool(isset($fields["old"]) ? $fields["old"] : false);

        $contact->setFirstName($firstName);
        $contact->setLastName($lastName);
        $contact->setEmail($email);
        $contact->setGender($gender);
        $contact->setFirm($firm);
        $contact->setTelephone($telephone);
        $contact->setCellphone($cellphone);
        $contact->setPosition($position);
        $contact->setNotes($notes);
        $contact->setOld($old);

        $this->contactDataService->persist($contact);

        return $contact;
    }

}