<?php


namespace Keros\Tools\Mail;


use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Member;
use Keros\Entities\Sg\ConsultantInscription;
use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use SendGrid\Mail\TypeException;

class MailFactory
{
    /** @var Logger */
    private $logger;
    
    /** @var MailSender */
    private $mailSender;

    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->mailSender = $container->get(MailSender::class);
    }

    /**
     * Envoie de mail pour la notification de l'inscription d'un membre
     *
     * @param MemberInscription $memberInscription
     */
    public function sendMailCreateMemberInscriptionFromTemplate(MemberInscription $memberInscription){
        $globalFields = array();
        $tos = array($memberInscription->getEmail() => array(
            "nom"=>$memberInscription->getFirstName()." ".$memberInscription->getLastName(),"full_name"=>"cc")
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_MEMBRE_INSCRIPTION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        } catch (KerosException | TypeException $e) {
            $this->logger->error("Failed to send MemberInscription mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail pour la validation de l'inscription d'un membre
     *
     * @param Member $member
     * @param String $password
     */
    public function sendMailMemberValidationFromTemplate(Member $member, String $password){
        $globalFields = array();
        $tos = array($member->getEmail() => array(
            "nom"=>$member->getFirstName()." ".$member->getLastName(),
            "identifiant"=>$member->getUser()->getUsername(),"mdp"=>$password)
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_MEMBRE_VALIDATION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException | TypeException $e) {
            $this->logger->error("Failed to send MemberValidation mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail pour la validation de l'inscription d'un consultant
     *
     * @param Consultant $consultant
     * @param String $password
     */
    public function sendMailConsultantValidationFromTemplate(Consultant $consultant, String $password){
        $globalFields = array();
        $tos = array($consultant->getEmail() => array(
            "nom"=>$consultant->getFirstName()." ".$consultant->getLastName(),
            "identifiant"=>$consultant->getUser()->getUsername(),"mdp"=>$password)
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_CONSULTANT_VALIDATION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException |TypeException $e) {
            $this->logger->error("Failed to send ConsultantValidation mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail pour la notification de l'inscription d'un consultant
     *
     * @param ConsultantInscription $consultantInscription
     */
    public function sendMailCreateConsultantInscriptionFromTemplate(ConsultantInscription $consultantInscription){
        $globalFields = array();
        $tos = array($consultantInscription->getEmail() => array(
            "nom"=>$consultantInscription->getFirstName()." ".$consultantInscription->getLastName())
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_CONSULTANT_INSCRIPTION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException |TypeException $e) {
            $this->logger->error("Failed to send ConsultantInscription mail : ".$e->getMessage());
        }
    }

}