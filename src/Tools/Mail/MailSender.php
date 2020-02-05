<?php

namespace Keros\Tools\Mail;

use Keros\Error\KerosException;

use Keros\Tools\ConfigLoader;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use \SendGrid as SendGrid;
use \SendGrid\Mail\From as From;
use \SendGrid\Mail\To as To;
use \SendGrid\Mail\Subject as Subject;
use \SendGrid\Mail\TemplateId as TemplateId;
use \SendGrid\Mail\Substitution as Substitution;
use \SendGrid\Mail\PlainTextContent as PlainTextContent;
use \SendGrid\Mail\HtmlContent as HtmlContent;
use \SendGrid\Mail\Mail as Mail;


/**
 * This whole tool is built based on SendGrid (https://github.com/sendgrid/sendgrid-php)
 * Class MailSender
 * @package Keros\Tools
 */
class MailSender
{
    /**
     * @var SendGrid
     */
    private $sender;

    /**
     * @var From
     */
    private $from;

    /**
     * @var array
     */
    protected $kerosConfig;

    /** @var Logger */
    private $logger;

    /**
     * MailSender constructor
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);

        $this->kerosConfig = ConfigLoader::getConfig();
        $this->sender = new SendGrid($this->kerosConfig['MAIL_API_KEY']);
        $this->from = new From("no-reply@etic-insa.com", "ETIC INSA Tech.");
    }

    public function setFrom(string $mail, string $name)
    {
        $this->from = new From($mail, $name);
    }

    /**
     * Creates mail from a pre-defined transactional template on SendGrid.
     * Can have multiple recipients.
     * 
     * @param string $templateName
     * String which has to correspond to the configuration variable found in settings.ini file.
     * @param array $globalFields
     * Dynamic field values that is applied globally (the same for all recipients).
     * This array has the following signature : [field_name: string] => [field_value: string]
     * @param bool $generic
     * Boolean value to determine to indicate whether an email is generic (does not contain recipient-specific dynamic fields)
     * @param array $tos
     * An array of recipients whose form depends on value of $generic.
     * If $generic is true, $tos is an array with emails as key and recipient's full name as value.
     * Signature : [email: string] => [full_name: string]
     * Else, $tos is an array with emails as key and an array of recipient-specific dynamic values as value.
     * Signature : [email: string] => [field_values: array]
     * In this case, the full_name key must exist as one of the entries in field_values.
     * @return Mail
     * @throws SendGrid\Mail\TypeException
     */
    public function createTemplateMail(string $templateName, array $globalFields, bool $generic, array $tos): Mail
    {
        $email = new Mail();

        $email->setFrom($this->from);

        if ($generic) {
            foreach($tos as $toMail => $toName) {
                $email->addTo(new To($toMail, $toName));
            }
            foreach($globalFields as $fieldName => $fieldValue) {
                $email->addDynamicTemplateData(new Substitution($fieldName, $fieldValue));
            }
        } else {
            $index = 0;
            foreach($tos as $toMail => $toFields) {
                $email->addTo(new To($toMail, $toFields["full_name"], array_merge($toFields, $globalFields)), null, null, $index++);
            }
        }

        $email->setTemplateId(new TemplateId($this->kerosConfig[$templateName]));
        return $email;
    }

    /**
     * Creates a simple mail meant for a single recipient.
     * 
     * @param string $subject
     * @param string $toMail
     * @param string $toName
     * @param string $htmlContent
     * @param string $plainTextContent
     * @return Mail
     */
    public function createSimpleMail(string $subject, string $toMail, string $toName, string $htmlContent, string $plainTextContent): Mail
    {
        $to = new To($toMail, $toName);
        $mailSubject = new Subject($subject);
        $mailHtmlContent = new HtmlContent($htmlContent);
        $mailPlainTextContent = new PlainTextContent($plainTextContent);

        return new Mail(
            $this->from,
            $to,
            $mailSubject,
            $mailPlainTextContent,
            $mailHtmlContent
        );
    }

    /**
     * @param Mail $mail
     * @throws KerosException
     */
    public function sendMail(Mail $mail)
    {
        if($this->kerosConfig['isTesting']){
            return;
        }
        $response = $this->sender->send($mail);
        $this->logger->debug($response->statusCode());
        if ($response->statusCode() != 202) {
            throw new KerosException($response->body(), $response->statusCode());
        }
    }
}

