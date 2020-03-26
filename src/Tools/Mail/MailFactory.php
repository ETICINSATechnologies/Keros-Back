<?php


namespace Keros\Tools\Mail;


use Keros\Entities\Core\Consultant;
use Keros\Entities\Core\Member;
use Keros\Entities\Core\User;
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
    public function sendMailCreateMemberInscriptionFromTemplate(MemberInscription $memberInscription)
    {
        $globalFields = array();
        $tos = array($memberInscription->getEmail() => array(
            "nom"=>$memberInscription->getFirstName()." ".$memberInscription->getLastName(),
            "full_name"=>$memberInscription->getFirstName()." ".$memberInscription->getLastName())
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_MEMBRE_INSCRIPTION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        } catch (KerosException | TypeException $e) {
            $this->logger->warning("Failed to send MemberInscription mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail à un un membre s'il est passé en Alumni
     * @param Member $member
     * @param String $token
     */
    public function sendMailMemberAlumniFromTemplate(Member $member)
    {
        $globalFields = array();
        $tos = array($member->getSendableMail() => array(
            "nom"=>$member->getFirstName()." ".$member->getLastName(),
            "full_name"=>$member->getFirstName()." ".$member->getLastName()
        ));
        $this->logger->debug("sending mail to". $member->getSendableMail());
        try {
            $email = $this->mailSender->createTemplateMail("MAIL_MEMBRE_ALUMNI",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException | TypeException $e) {
            $this->logger->warning("Failed to send MemberAlumni mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail pour la validation de l'inscription d'un membre
     *
     * @param Member $member
     * @param String $password
     */
    public function sendMailMemberValidationFromTemplate(Member $member, String $password)
    {
        $globalFields = array();
        $tos = array($member->getSendableMail() => array(
            "nom"=>$member->getFirstName()." ".$member->getLastName(),
            "identifiant"=>$member->getUser()->getUsername(),
            "mdp"=>$password,
            "full_name"=>$member->getFirstName()." ".$member->getLastName())
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_MEMBRE_VALIDATION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException | TypeException $e) {
            $this->logger->warning("Failed to send MemberValidation mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail pour la validation de l'inscription d'un consultant
     *
     * @param Consultant $consultant
     * @param String $password
     */
    public function sendMailConsultantValidationFromTemplate(Consultant $consultant, String $password)
    {
        $globalFields = array();
        $tos = array($consultant->getEmail() => array(
            "nom"=>$consultant->getFirstName()." ".$consultant->getLastName(),
            "identifiant"=>$consultant->getUser()->getUsername(),"mdp"=>$password,"full_name"=>$consultant->getFirstName()." ".$consultant->getLastName())
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_CONSULTANT_VALIDATION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException |TypeException $e) {
            $this->logger->warning("Failed to send ConsultantValidation mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail pour la notification de l'inscription d'un consultant
     *
     * @param ConsultantInscription $consultantInscription
     */
    public function sendMailCreateConsultantInscriptionFromTemplate(ConsultantInscription $consultantInscription)
    {
        $globalFields = array();
        $tos = array($consultantInscription->getEmail() => array(
            "nom"=>$consultantInscription->getFirstName()." ".$consultantInscription->getLastName(),"full_name"=>$consultantInscription->getFirstName()." ".$consultantInscription->getLastName())
        );

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_CONSULTANT_INSCRIPTION",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException |TypeException $e) {
            $this->logger->warning("Failed to send ConsultantInscription mail : ".$e->getMessage());
        }
    }

    /**
     * Envoie de mail avec un token pour reinitialiser le mot de passe
     * @param Member $member
     * @param String $token
     */
    public function sendMailResetMpTokenEnvoie(Member $member, string $token)
    {
        $globalFields = array();
        $tos = array($member->getSendableMail() => array(
            "nom"=>$member->getFirstName()." ".$member->getLastName(),
            "full_name"=>$member->getFirstName()." ".$member->getLastName(),
            "token"=>$token,
        ));

        try {
            $email = $this->mailSender->createTemplateMail("MAIL_RESET_MP_TOKEN_ENVOIE",$globalFields,false,$tos);
            $this->mailSender->sendMail($email);
        }catch (KerosException | TypeException $e) {
            $this->logger->warning("Failed to send sendTokenForReset mail : ".$e->getMessage());
        }
    }


}