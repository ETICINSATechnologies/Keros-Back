<?php

namespace Keros\Services\Ua;


use Keros\Error\KerosException;
use Keros\Services\Core\MemberService;
use Keros\Services\Core\TemplateService;
use Keros\Tools\GenderBuilder;
use Psr\Container\ContainerInterface;
use Keros\Entities\Ua\Study;
use Keros\Entities\Core\Member;


class StudyTemplateService extends TemplateService
{

    /**
     * @var StudyService
     */
    private $studyService;

    /**
     * @var GenderBuilder
     */
    private $genderBuilder;

    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->studyService = $container->get(StudyService::class);
        $this->memberService = $container->get(MemberService::class);
        $this->genderBuilder = $container->get(GenderBuilder::class);
    }

    /**
     * @param int $templateId
     * @param int $studyId
     * @param int $connectedUserId
     * @return string
     * @throws \Exception
     */
    public function generateStudyDocument(int $templateId, int $studyId, int $connectedUserId): string
    {
        $study = $this->studyService->getOne($studyId);
        $template = $this->getOne($templateId);
        $connectedUser = $this->memberService->getOne($connectedUserId);

        if ($study->getContacts() == null || empty($study->getContacts())) {
            $msg = "No contact in study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        if (!$this->studyService->consultantAreValid($study->getId())) {
            $msg = "Invalid consultant in study " . $study->getId();
            $this->logger->error($msg);
            throw new KerosException($msg, 400);
        }

        //Zip are done if one document per consultant is needed
        $doZip = $template->getOneConsultant() == 1;
        $searchArray = $this->getStudySearchArray();

        if ($doZip) {
            $replacementArrays = array();
            foreach ($study->getConsultantsArray() as $consultant)
                $replacementArrays[] = $this->getStudyReplacementArray($study, $connectedUser, array($consultant));
            $location = $this->templateDataService->generateMultipleDocument($template, $searchArray, $replacementArrays);
        } else {
            $replacementArray = $this->getStudyReplacementArray($study, $connectedUser, $study->getConsultantsArray());
            $this->logger->info(json_encode($replacementArray));
            $location = $this->templateDataService->generateSimpleDocument($template, $searchArray, $replacementArray);
        }
        return $location;
    }

    /**
     * To add pattern, add here and in :getReplacementArray AT THE SAME INDEX
     * @return array
     */
    public function getStudySearchArray(): array
    {
        return array(
            '${NOMENTREPRISE}',
            '${TITREETUDE}',
            '${ADRESSEENTREPRISE}',
            '${CPENTREPRISE}',
            '${VILLEENTREPRISE}',
            '${SIRETENTREPRISE}',
            '${DESCRIPTIONETUDE}',
            '${DATESIGCV}',
            '${FCTCONTACT}',
            '${CIVILITECONTACT}',
            '${PRENOMCONTACT}',
            '${NOMCONTACT}',
            '${MAILCONTACT}',
            '${DJOUR}',
            '${NUMINTERVENANT}',
            '${CIVILITEINTERVENANT}',
            '${PRENOMINTERVENANT}',
            '${NOMINTERVENANT}',
            '${MAILINTERVENANT}',
            '${ADRESSEINTERVENANT}',
            '${CPINTERVENANT}',
            '${VILLEINTERVENANT}',
            '${NOMUSER}',
            '${PRENOMUSER}',
            '${CIVILITEUSER}',
            '${IDENTITECONTACT}',
            '${IDENTITEINTERVENANT}',
            '${INDENTITEUSER}',
            '${DATEFIN}',
            '${NOMPRESIDENT}',
            '${CIVPRESIDENT}',
            '${PRENOMPRESIDENT}',
            '${NOMTRESORIER}',
            '${CIVTRESORIER}',
            '${PRENOMTRESORIER}',
            '${IDENTITETRESORIER}',
            '${IDENTITEPRESIDENT}'
        );
    }

    /**
     * To add replacement, add here and in :getSearchArray AT THE SAME INDEX
     * @param Study $study
     * @param Member $connectedUser
     * @param Member[] $consultants
     * @return array
     * @throws \Exception
     */
    public function getStudyReplacementArray(Study $study, Member $connectedUser, array $consultants): array
    {
        $contact = $study->getContacts()[0];
        $date = new \DateTime();

        $consultantsIdentity = '';
        $nbConsultant = 0;
        //loop to have multiple consultant identity correctly
        foreach ($study->getConsultantsArray() as $consultant) {
            $consultantsIdentity .= $this->genderBuilder->getStringGender($consultant->getGender()) . ' ' . $consultant->getLastName() . ' ' . $consultant->getFirstName();
            //If we are not one the last consultant in the array
            if (++$nbConsultant !== count($study->getConsultantsArray()))
                $consultantsIdentity .= ', ';
        }

        //Information about actual board
        $tresorier = null;
        $president = null;
        $board = $this->memberService->getLatestBoard();
        foreach ($board as $member) {
            foreach ($member->getPositionsArray() as $position) {
                if ($position->getIsBoard()) {
                    if ($position->getPosition()->getId() == 23)
                        $tresorier = $position->getMember();
                    else if ($position->getPosition()->getId() == 14)
                        $president = $position->getMember();
                }
            }
        }

        return array(
            $study->getFirm()->getName(),
            $study->getName(),
            $study->getFirm()->getAddress()->getLine1() . ", " . $study->getFirm()->getAddress()->getLine2(),
            $study->getFirm()->getAddress()->getPostalCode(),
            $study->getFirm()->getAddress()->getCity(),
            ($study->getFirm()->getSiret() != null) ? $study->getFirm()->getSiret() : '${SIRETENTREPRISE}',
            ($study->getDescription() != null) ? $study->getDescription() : '${DESCRIPTIONETUDE}',
            ($study->getSignDate() != null) ? $study->getSignDate()->format('d/m/Y') : '${DATESIGCV}',
            ($contact->getPosition() != null) ? $contact->getPosition() != null : '${FCTCONTACT}',
            $this->genderBuilder->getStringGender($contact->getGender()),
            $contact->getFirstName(),
            $contact->getLastName(),
            $contact->getEmail(),
            $date->format('d/m/Y'),
            ($consultants[0] != null) ? $consultants[0]->getId() : '${NUMINTERVENANT}',
            ($consultants[0] != null) ? $this->genderBuilder->getStringGender($consultants[0]->getGender()) : '${CIVILITEINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getFirstName() : '${PRENOMINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getLastName() : '${NOMINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getEmail() : '${MAILINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getLine1() . (($consultants[0]->getAddress()->getLine2() != null) ? ' ' . $consultants[0]->getAddress()->getLine2() : '') : '${ADRESSEINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getPostalCode() : '${CPINTERVENANT}',
            ($consultants[0] != null) ? $consultants[0]->getAddress()->getCity() : '${VILLEINTERVENANT}',
            $connectedUser->getLastName(),
            $connectedUser->getFirstName(),
            $this->genderBuilder->getStringGender($connectedUser->getGender()),
            $this->genderBuilder->getStringGender($contact->getGender()) . ' ' . $contact->getLastName() . ' ' . $contact->getFirstName(),
            $consultantsIdentity,
            $this->genderBuilder->getStringGender($connectedUser->getGender()) . ' ' . $connectedUser->getLastName() . ' ' . $connectedUser->getFirstName(),
            ($study->getArchivedDate() != null) ? $study->getArchivedDate()->format('d/m/Y') : '${DATEFIN}',
            ($president != null) ? $president->getLastName() : '${NOMPRESIDENT}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) : '${CIVPRESIDENT}',
            ($president != null) ? $president->getFirstName() : '${PRENOMPRESIDENT}',
            ($tresorier != null) ? $tresorier->getLastName() : '${NOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) : '${CIVTRESORIER}',
            ($tresorier != null) ? $tresorier->getFirstName() : '${PRENOMTRESORIER}',
            ($tresorier != null) ? $this->genderBuilder->getStringGender($tresorier->getGender()) . ' ' . $tresorier->getLastName() . ' ' . $tresorier->getFirstName() : '${IDENTITETRESORIER}',
            ($president != null) ? $this->genderBuilder->getStringGender($president->getGender()) . ' ' . $president->getLastName() . ' ' . $president->getFirstName() : '${IDENTITEPRESIDENT}',
        );
    }
}