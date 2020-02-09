<?php


namespace Keros\Services\Sg;

use DateTime;
use Keros\DataServices\Sg\MemberInscriptionDataService;
use Keros\Entities\Core\RequestParameters;
use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;
use Keros\Services\Core\AddressService;
use Keros\Services\Core\CountryService;
use Keros\Services\Core\DepartmentService;
use Keros\Services\Core\GenderService;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\PoleService;
use Keros\Tools\Mail\MailFactory;
use Keros\Tools\Validator;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class   MemberInscriptionService
{
    /** @var AddressService */
    private $addressService;

    /** @var GenderService */
    private $genderService;

    /** @var MemberService */
    private $memberService;

    /** @var MemberInscriptionDataService */
    private $memberInscriptionDataService;

    /** @var CountryService */
    private $countryService;

    /** @var DepartmentService */
    private $departmentService;

    /** @var PoleService */
    private $poleService;

    /** @var Logger */
    private $logger;

    /** @var MemberInscriptionDocumentService */
    private $memberInscriptionDocumentService;

    /** @var MailFactory */
    private $mailFactory;

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
        $this->memberInscriptionDocumentService = $container->get(MemberInscriptionDocumentService::class);
        $this->mailFactory = $container->get(MailFactory::class);
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
        $genderId = Validator::requiredId($fields['genderId']);
        $gender = $this->genderService->getOne($genderId);
        $birthday = Validator::requiredDate($fields['birthday']);
        $hasPaid = false;
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $createdDate = new \DateTime();

        $address = $this->addressService->create($fields["address"]);
        $memberInscription = new MemberInscription($firstName, $lastName, $gender, $birthday, $department, $email, $phoneNumber, $outYear, $nationality, $address, $wantedPole, $hasPaid, $droitImage, $createdDate, array());

        $this->memberInscriptionDataService->persist($memberInscription);

        $this->mailFactory->sendMailCreateMemberInscriptionFromTemplate($memberInscription);


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
        $memberInscriptionDocuments = $memberInscription->getMemberInscriptionDocumentsArray();
        foreach ($memberInscriptionDocuments as $memberInscriptionDocument) {
            $member = $memberInscriptionDocument->getMember();
            $this->memberInscriptionDocumentService->update($memberInscriptionDocument->getId(), null, $member);
            if ($member == null)
                $this->memberInscriptionDocumentService->delete($memberInscriptionDocument->getId());

        }
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
            throw new KerosException("The member_inscription " . $id . " could not be found", 404);
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

        $firstName = Validator::requiredString($fields["firstName"]);
        $lastName = Validator::requiredString($fields["lastName"]);
        $departmentId = Validator::requiredId($fields["departmentId"]);
        $department = $this->departmentService->getOne($departmentId);
        $email = Validator::requiredEmail($fields["email"]);
        $phoneNumber = Validator::optionalPhone(isset($fields["phoneNumber"]) ? $fields["phoneNumber"] : $memberInscription->getPhoneNumber());
        $outYear = Validator::optionalInt(isset($fields["outYear"]) ? $fields["outYear"] : $memberInscription->getOutYear());
        $nationalityId = Validator::requiredId($fields["nationalityId"]);
        $nationality = $this->countryService->getOne($nationalityId);
        $wantedPoleId = Validator::requiredId($fields["wantedPoleId"]);
        $wantedPole = $this->poleService->getOne($wantedPoleId);
        $genderId = Validator::requiredId($fields['genderId']);
        $gender = $this->genderService->getOne($genderId);
        $birthday = Validator::requiredDate($fields['birthday']);
        $droitImage = Validator::requiredBool($fields['droitImage']);

        $memberInscription->setFirstName($firstName);
        $memberInscription->setLastName($lastName);
        $memberInscription->setDepartment($department);
        $memberInscription->setEmail($email);
        $memberInscription->setPhoneNumber($phoneNumber);
        $memberInscription->setOutYear($outYear);
        $memberInscription->setNationality($nationality);
        $memberInscription->setWantedPole($wantedPole);
        $memberInscription->setGender($gender);
        $memberInscription->setBirthday($birthday);
        $memberInscription->setDroitImage($droitImage);
        $this->addressService->update($memberInscription->getAddress()->getId(), $fields["address"]);

        $this->memberInscriptionDataService->persist($memberInscription);

        return $memberInscription;
    }

    /**
     * @param int $id
     * @throws KerosException
     */
    public function validateMemberInscription(int $id)
    {
        $id = Validator::requiredId($id);
        $memberInscription = $this->getOne($id);
        $date = new DateTime();
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        $memberArray = array(
            "username" => $memberInscription->getFirstName() . '.' . $memberInscription->getLastName(),
            "password" => $memberInscription->getFirstName() . '.' . $memberInscription->getBirthday()->format('d/m/Y'),
            "firstName" => $memberInscription->getFirstName(),
            "lastName" => $memberInscription->getLastName(),
            "email" => $memberInscription->getEmail(),
            "telephone" => $memberInscription->getPhoneNumber(),
            "birthday" => $memberInscription->getBirthday()->format('Y-m-d'),
            "genderId" => $memberInscription->getGender()->getId(),
            "departmentId" => $memberInscription->getDepartment()->getId(),
            "company" => null,
            "profilePicture" => null,
            "disabled" => false,
            "address" => array(
                "line1" => $memberInscription->getAddress()->getLine1(),
                "line2" => $memberInscription->getAddress()->getLine2(),
                "postalCode" => $memberInscription->getAddress()->getPostalCode(),
                "city" => $memberInscription->getAddress()->getCity(),
                "countryId" => $memberInscription->getAddress()->getCountry()->getId()
            ),
            "positions" => array(),
            "droitImage" => $memberInscription->isDroitImage(),
            "createdDate" => new \DateTime(),
            'isGraduate' => false,
        );

        if ($memberInscription->getOutYear()) {
            $schoolYear = 5 - ($memberInscription->getOutYear() - $year);
            if ($month > 8 && $month <= 12) //between September and December
                $schoolYear += 1;
            $memberArray["schoolYear"] = $schoolYear;
        }

        $member = $this->memberService->create($memberArray);
        foreach ($memberInscription->getMemberInscriptionDocumentsArray() as $memberInscriptionDocument) {
            $this->memberInscriptionDocumentService->update($memberInscriptionDocument->getId(), null, $member);
        }

        $this->mailFactory->sendMailMemberValidationFromTemplate($member,$memberArray["password"]);

        $this->delete($memberInscription->getId());
    }

    /**
     * @param int $id
     * @return MemberInscription
     * @throws KerosException
     */
    public function confirmPaymentMemberInscription(int $id): MemberInscription
    {
        $id = Validator::requiredId($id);
        $memberInscription = $this->getOne($id);

        $memberInscription->setHasPaid(true);

        $this->memberInscriptionDataService->persist($memberInscription);

        return $memberInscription;
    }
}