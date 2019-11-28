<?php

namespace Keros\Tools;

use Keros\Entities\Sg\MemberInscription;
use Keros\Error\KerosException;

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
use Throwable;


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
       // $this->sender = new SendGrid($this->kerosConfig['MAIL_TEST_API_KEY']);
        $this->sender = new SendGrid("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");
       // $this->from = new From("no-reply@etic-insa.com", "ETIC INSA Tech.");
        $this->from = new From("adonis.kattan@insa-lyon.fr", "Adonis Test");

        $this->logger->debug("MailSender constructeur");
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
* @param Object $memberInscription
*/
    public function sendMailMemberInscriptionFromTemplate(MemberInscription $memberInscription){
        $globalFields = array();
        $tos = array($memberInscription->getEmail() => array(
            "nom"=>$memberInscription->getFirstName()." ".$memberInscription->getLastName(),
            "full_name"=>"Bonsoir")
        );
        $email = $this->createTemplateMail("MAIL_TEST_TEMPLATE",$globalFields,false,$tos);

        try {
            $this->sendMail($email);
        }catch (Throwable $th){
            print $th->getMessage();
        }
    }

    /**
     * @param Object $memberInscription
     */
    public function sendMailMemberValidationFromTemplate(MemberInscription $memberInscription){
        $globalFields = array();
        $tos = array($memberInscription->getEmail() => array(
            "nom"=>$memberInscription->getFirstName()." ".$memberInscription->getLastName(),
            "full_name"=>"Bonsoir")
        );
        $email = $this->createTemplateMail("MAIL_TEST_TEMPLATE",$globalFields,false,$tos);

        try {
            $this->sendMail($email);
        }catch (Throwable $th){
            print $th->getMessage();
        }
    }



    /**
     * Sends an email to notify users that their subscription is awaiting validation
     *
     * @param Object $json
     */
    public function sendMailInscription(Object $json){
                    $mail = $this->createSimpleMail("Inscription en cours de validation",$json->email,$json->firstName+$json->lastName,"
                                        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
                                <html data-editor-version=\"2\" class=\"sg-campaigns\" xmlns=\"http://www.w3.org/1999/xhtml\">
                                  <head>
                                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
                                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1\" /><!--[if !mso]><!-->
                                    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=Edge\" /><!--<![endif]-->
                                    <!--[if (gte mso 9)|(IE)]>
                                    <xml>
                                    <o:OfficeDocumentSettings>
                                    <o:AllowPNG/>
                                    <o:PixelsPerInch>96</o:PixelsPerInch>
                                    </o:OfficeDocumentSettings>
                                    </xml>
                                    <![endif]-->
                                    <!--[if (gte mso 9)|(IE)]>
                                    <style type=\"text/css\">
                                      body {width: 600px;margin: 0 auto;}
                                      table {border-collapse: collapse;}
                                      table, td {mso-table-lspace: 0pt;mso-table-rspace: 0pt;}
                                      img {-ms-interpolation-mode: bicubic;}
                                    </style>
                                    <![endif]-->
                                
                                    <style type=\"text/css\">
                                      body, p, div {
                                        font-family: arial;
                                        font-size: 14px;
                                      }
                                      body {
                                        color: #000000;
                                      }
                                      body a {
                                        color: #1188E6;
                                        text-decoration: none;
                                      }
                                      p { margin: 0; padding: 0; }
                                      table.wrapper {
                                        width:100% !important;
                                        table-layout: fixed;
                                        -webkit-font-smoothing: antialiased;
                                        -webkit-text-size-adjust: 100%;
                                        -moz-text-size-adjust: 100%;
                                        -ms-text-size-adjust: 100%;
                                      }
                                      img.max-width {
                                        max-width: 100% !important;
                                      }
                                      .column.of-2 {
                                        width: 50%;
                                      }
                                      .column.of-3 {
                                        width: 33.333%;
                                      }
                                      .column.of-4 {
                                        width: 25%;
                                      }
                                      @media screen and (max-width:480px) {
                                        .preheader .rightColumnContent,
                                        .footer .rightColumnContent {
                                            text-align: left !important;
                                        }
                                        .preheader .rightColumnContent div,
                                        .preheader .rightColumnContent span,
                                        .footer .rightColumnContent div,
                                        .footer .rightColumnContent span {
                                          text-align: left !important;
                                        }
                                        .preheader .rightColumnContent,
                                        .preheader .leftColumnContent {
                                          font-size: 80% !important;
                                          padding: 5px 0;
                                        }
                                        table.wrapper-mobile {
                                          width: 100% !important;
                                          table-layout: fixed;
                                        }
                                        img.max-width {
                                          height: auto !important;
                                          max-width: 480px !important;
                                        }
                                        a.bulletproof-button {
                                          display: block !important;
                                          width: auto !important;
                                          font-size: 80%;
                                          padding-left: 0 !important;
                                          padding-right: 0 !important;
                                        }
                                        .columns {
                                          width: 100% !important;
                                        }
                                        .column {
                                          display: block !important;
                                          width: 100% !important;
                                          padding-left: 0 !important;
                                          padding-right: 0 !important;
                                          margin-left: 0 !important;
                                          margin-right: 0 !important;
                                        }
                                      }
                                    </style>
                                    <!--user entered Head Start-->
                                    
                                     <!--End Head user entered-->
                                  </head>
                                  <body>
                                    <center class=\"wrapper\" data-link-color=\"#1188E6\" data-body-style=\"font-size: 14px; font-family: arial; color: #000000; background-color: #ffffff;\">
                                      <div class=\"webkit\">
                                        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" class=\"wrapper\" bgcolor=\"#ffffff\">
                                          <tr>
                                            <td valign=\"top\" bgcolor=\"#ffffff\" width=\"100%\">
                                              <table width=\"100%\" role=\"content-container\" class=\"outer\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                                <tr>
                                                  <td width=\"100%\">
                                                    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                                      <tr>
                                                        <td>
                                                          <!--[if mso]>
                                                          <center>
                                                          <table><tr><td width=\"600\">
                                                          <![endif]-->
                                                          <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width: 100%; max-width:600px;\" align=\"center\">
                                                            <tr>
                                                              <td role=\"modules-container\" style=\"padding: 0px 0px 0px 0px; color: #000000; text-align: left;\" bgcolor=\"#ffffff\" width=\"100%\" align=\"left\">
                                                                
                                    <table class=\"module preheader preheader-hide\" role=\"module\" data-type=\"preheader\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"
                                           style=\"display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;\">
                                      <tr>
                                        <td role=\"module-content\">
                                          <p></p>
                                        </td>
                                      </tr>
                                    </table>
                                  
                                    <table class=\"wrapper\" role=\"module\" data-type=\"image\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout: fixed;\">
                                      <tr>
                                        <td style=\"font-size:6px;line-height:10px;padding:0px 0px 0px 0px;\" valign=\"top\" align=\"center\">
                                          <img class=\"max-width\" border=\"0\" style=\"display:block;color:#000000;text-decoration:none;font-family:Helvetica, arial, sans-serif;font-size:16px;max-width:100% !important;width:100%;height:auto !important;\" src=\"https://marketing-image-production.s3.amazonaws.com/uploads/70ddb22b3c6b713a5fb554015e8d3c26a36fa1f329d53924e21f5853c4895d4d93cbddcf245073f305a0c024403fc2eb83aab851c79d69f989d38a2f75c4d223.png\" alt=\"\" width=\"600\">
                                        </td>
                                      </tr>
                                    </table>
                                  
                                    <table class=\"module\" role=\"module\" data-type=\"text\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout: fixed;\">
                                      <tr>
                                        <td style=\"padding:18px 0px 18px 0px;line-height:22px;text-align:inherit;\"
                                            height=\"100%\"
                                            valign=\"top\"
                                            bgcolor=\"\">
                                            <div>Bonjour $json->firstName $json->lastName,</div>
                                
                                <div>Votre inscription est en attente et sera tr&egrave;s prochainement valid&eacute;e.</div>
                                
                                <div>En attendant n&#39;h&eacute;sitez pas de passer au local et nous contacter si vous avez des questions !</div>
                                
                                <div>Bonne journ&eacute;e &agrave; vous</div>
                                
                                        </td>
                                      </tr>
                                    </table>
                                  <div data-role=\"module-unsubscribe\" class=\"module unsubscribe-css__unsubscribe___2CDlR\" role=\"module\" data-type=\"unsubscribe\" style=\"color:#444444;font-size:12px;line-height:20px;padding:16px 16px 16px 16px;text-align:center\"><div class=\"Unsubscribe--addressLine\"><p class=\"Unsubscribe--senderName\" style=\"font-family:Arial,Helvetica, sans-serif;font-size:12px;line-height:20px\">[Sender_Name]</p><p style=\"font-family:Arial,Helvetica, sans-serif;font-size:12px;line-height:20px\"><span class=\"Unsubscribe--senderAddress\">[Sender_Address]</span>, <span class=\"Unsubscribe--senderCity\">[Sender_City]</span>, <span class=\"Unsubscribe--senderState\">[Sender_State]</span> <span class=\"Unsubscribe--senderZip\">[Sender_Zip]</span> </p></div><p style=\"font-family:Arial,Helvetica, sans-serif;font-size:12px;line-height:20px\"><a class=\"Unsubscribe--unsubscribeLink\" href=\"<%asm_group_unsubscribe_raw_url%>\">Unsubscribe</a> - <a class=\"Unsubscribe--unsubscribePreferences\" href=\"<%asm_preferences_raw_url%>\">Unsubscribe Preferences</a></p></div>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                          <!--[if mso]>
                                                          </td></tr></table>
                                                          </center>
                                                          <![endif]-->
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  </td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </div>
                                    </center>
                                  </body>
                                </html>
            "," ");
        try {
            $this->sendMail($mail);
        }catch (Throwable $th){
            print $th->getMessage();
        }
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
        $this->logger->debug($response->body());
        if ($response->statusCode() != 202) {
            throw new KerosException($response->body(), $response->statusCode());
        }
    }
}

