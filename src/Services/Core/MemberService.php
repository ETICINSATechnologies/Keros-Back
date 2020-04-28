<?php

namespace Keros\Services\Core;

use Exception;
use Keros\DataServices\Core\MemberDataService;
use Keros\DataServices\Core\TicketDataService;
use Keros\DataServices\Treso\PaymentSlipDataService;
use Keros\Entities\Auth\LoginResponse;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\Page;
use Keros\Entities\Core\RequestParameters;
use Keros\Error\KerosException;
use Keros\Services\Sg\MemberInscriptionDocumentService;
use Keros\Tools\Authorization\JwtCodec;
use Keros\Tools\Authorization\PasswordEncryption;
use Keros\Tools\ConfigLoader;
use Keros\Tools\DirectoryManager;
use Keros\Tools\Validator;
use Keros\Tools\Mail\MailFactory;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use SendGrid\Mail\Mail;
use Stripe\Stripe;

class MemberService
{
    /** @var AddressService */
    private $addressService;

    /** @var MailFactory */
    private $mailFactory;

    /** @var GenderService */
    private $genderService;

    /** @var UserService */
    private $userService;

    /** @var DepartmentService */
    private $departmentService;

    /** @var TicketDataService */
    private $ticketDataService;

    /**
     * @var PaymentSlipDataService
     */
    private $paymentSlipDataService;

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

    /** @var JwtCodec */
    private $jwtCodec;

    /** @var MemberInscriptionDocumentService */
    private $memberInscriptionDocumentService;

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
        $this->paymentSlipDataService = $container->get(PaymentSlipDataService::class);
        $this->directoryManager = $container->get(DirectoryManager::class);
        $this->kerosConfig = ConfigLoader::getConfig();
        $this->memberInscriptionDocumentService = $container->get(MemberInscriptionDocumentService::class);
        $this->jwtCodec = $container->get(JwtCodec::class);
        $this->mailFactory=$container->get(MailFactory::class);
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
        $telephone = Validator::requiredPhone(isset($fields["telephone"]) ? $fields["telephone"] : null);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear(isset($fields["schoolYear"]) ? $fields["schoolYear"] : null);

        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::requiredId(isset($fields["departmentId"]) ? $fields["departmentId"] : null);
        $department = $this->departmentService->getOne($departmentId);
        $createdDate = new \DateTime();
        $company = Validator::optionalString($fields["company"]);
        $profilePicture = null;
        $droitImage = Validator::requiredBool($fields['droitImage']);
        $isAlumni = Validator::optionalBool($fields['isAlumni'] ?? false);
        $emailETIC = Validator::optionalEmail($fields["emailETIC"] ?? null);
        $dateRepayment = Validator::requiredDate($fields["dateRepayment"]);

        $member = new Member($firstName, $lastName, $birthday, $telephone, $email, $schoolYear, $gender, $department, $company, $profilePicture, $droitImage, $createdDate, $isAlumni, array(), $emailETIC, $dateRepayment);

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
        $telephone = Validator::requiredPhone($fields["telephone"]);
        $birthday = Validator::requiredDate($fields["birthday"]);
        $schoolYear = Validator::requiredSchoolYear($fields["schoolYear"]);
        $genderId = Validator::requiredId($fields["genderId"]);
        $gender = $this->genderService->getOne($genderId);
        $department = null;
        $departmentId = Validator::requiredId($fields["departmentId"]);
        $department = $this->departmentService->getOne($departmentId);
        $emailETIC = Validator::optionalEmail($fields["emailETIC"] ?? null);

        $company = Validator::optionalString(isset($fields["company"]) ? $fields["company"] : $member->getCompany());
        $isAlumni = Validator::optionalBool($fields['isAlumni'] ?? $member->getIsAlumni());
        $droitImage = Validator::optionalBool($fields['droitImage'] ?? $member->isDroitImage());
        if(!empty($isAlumni)){
            if((!$member->getIsAlumni()) && $fields['isAlumni']){
                $this->mailFactory->sendMailMemberAlumniFromTemplate($member);
            }
        }
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
        $member->setIsAlumni($isAlumni);
        $member->setEmailETIC($emailETIC);
        $member->setDroitImage($droitImage);

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
        foreach ($member->getMemberInscriptionDocumentsArray() as $memberInscriptionsDocument) {
            $this->memberInscriptionDocumentService->delete($memberInscriptionsDocument->getId());
        }
        $member->setMemberInscriptionDocuments([]);
        $this->memberDataService->delete($member);
        $this->userService->delete($id);
        $this->addressService->delete($address->getId());
        if($profilepicture != null) {
            $filepath = $this->directoryManager->normalizePath($this->kerosConfig['MEMBER_PHOTO_DIRECTORY'] . $profilepicture);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
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

        $filename = $this->directoryManager->uniqueFilenameOnly($file, false, $this->kerosConfig['MEMBER_PHOTO_DIRECTORY']);

        $this->directoryManager->mkdir($this->kerosConfig['MEMBER_PHOTO_DIRECTORY']);
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

    public function findMemberByEmail(String $email)
    {
        $email = Validator::requiredEmail($email);

        $member = $this->memberDataService->findByEmail($email);

        if (is_null($member)) {
            throw new KerosException("The member with this email could not be found", 404);
        }

        return $member;
    }

    public function sendTokenForReset(array $body)
    {

        $member = $this->findMemberByEmail($body['email']);

        if (is_null($member)) {
            throw new KerosException("The member doesn't exist", 404);
        }

        $user = $member->getUser();

        // the token will expire in exactly in two hours
        $exp = time() + 2 * 3600;

        // creation of the payload
        $payload = array(
            "id" => $user->getId(),
            "exp" => $exp
        );

        // create the token from the payload
        $token = $this->jwtCodec->encode($payload);

        //send email
        $this->mailFactory->sendMailResetMpTokenEnvoie($member, $token);
    }

    public function decryptTokenForReset($token)
    {

        if (!isset($token)) {
            throw new KerosException("token wasn't found", 404);
        }

        $payload = $this->jwtCodec->decode($token);

        //check if user does exist
        if (is_null($this->userService->getOne($payload->id))) {
            throw new KerosException("User doesn't exist", 404);
        }

        //check if token has expired
        if ($payload->exp < time()) {
            throw new KerosException("Reset MP token has expired, please reset again", 403);
        }

        return $payload->id;
	}

	public function export($idList)
	{
		if (!isset($idList)) {
			throw new KerosException("idList not specified.", 400);
		}

		$members = $this->getSome($idList);

		if (empty($members)) {
			throw new KerosException("No members found.", 404);
		}

		$filepath = $this->kerosConfig['TEMPORARY_DIRECTORY'] . "members" . (new \DateTime("NOW"))->format("Y-m-d;H:i:s") . ".csv";
		$csvFile = fopen($filepath, "w+");

		$tags = array(
			'Pole' => 'positions',
			'Nom' => 'lastName',
			'Prenom' => 'firstName',
			'Sexe' => 'gender',
			'Annee'=> 'schoolYear',
			'Departement' => 'department',
			'Telephone' => 'telephone',
			'Adresse Mail' => 'email',
			'Recrutement' => 'createdDate',
			'Statut' => 'status',
			'Naissance' => 'birthday'
		);
		fputcsv($csvFile, array_keys($tags));

		foreach ($members as $member) {
			$memberData = $member->jsonSerialize();
			$data = array();
			foreach ($tags as $value){
				switch($value) {
					case 'schoolYear':
					case 'firstName':
					case 'lastName':
					case 'telephone':
					case 'email':
					case 'birthday':
						$data[] = $memberData[$value];
						break;
					case 'createdDate':
						$data[] = $memberData[$value]->format('Y-m-d');
						break;
					case 'gender':
					case 'department':
						$data[] = $memberData[$value]->getLabel();
						break;
					case 'positions':
						if (!empty($memberData[$value])) {
							$latestYear = $memberData[$value][0]->getYear();
							$posPoles = array_filter($memberData[$value], function($pos) use ($latestYear) {
								return ($pos->getYear() == $latestYear) && $pos->getPosition()->getPole();
							});
							$poles = array_unique(array_map(function($position) {
								return $position->getPosition()->getPole()->getName();
							}, $posPoles));

							if (!empty($poles)) {
								$data[] = implode(" & ", $poles);
							} else {
								$data[] = " ";
							}
						} else {
							$data[] = " ";
						}
						break;
					case 'status':
						if ($memberData['isAlumni']) {
							$data[] = 'Ancien';
						} else if ($memberData['createdDate']->diff(new \DateTime('NOW'))->format("%a") / 365 > 1) {
							$data[] = 'Senior';
						} else {
							$data[] = 'Junior';
						}
						break;
					default:
						break;
				}
			}
			fputcsv($csvFile, $data);
		}
		fclose($csvFile);
		return pathinfo($filepath, PATHINFO_BASENAME);
	}

	public function updateMembersPaymentDate(String $payload, String $sig_header)
    {
        Stripe::setApiKey($this->kerosConfig['API_KEY']);

        $endpoint_secret = $this->kerosConfig['ENDPOINT_SECRET'];

        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );

        } catch(\UnexpectedValueException $e) {
            throw new KerosException("Invalid payload", 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            throw new KerosException("Invalid signature", 400);
        }

        // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
            $session  = $event->data->object->id;   // contains a StripeSession

            // Fetch the session JSON
            $sessionInfo = \Stripe\Checkout\Session::retrieve($session);

            //get client_reference_id
            $client_reference_id = $sessionInfo->client_reference_id;

            if (isset($client_reference_id)) {
                $member = $this->memberDataService->getOne($client_reference_id);
                $member->setDateRepayment(new \DateTime("now"));
                $this->logger->debug("La date de paiement du membre id = " . $client_reference_id . " est mise à jour");
            } else {
                $this->logger->error("ID du membre est null");
            }
        }
    }
}
