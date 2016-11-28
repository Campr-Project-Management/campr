<?php

namespace AppBundle\Services;

/**
 * Class MailerService
 * Service used for sending emails.
 */
class MailerService
{
    private $mailer;

    private $twig;

    private $parameters = [];

    /**
     * MailerService constructor.
     *
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     * @param $fromParameters
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, $fromParameters)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameters = $fromParameters;
    }

    /**
     * @param string       $templateName
     * @param string       $fromEmail
     * @param string|array $toEmail
     * @param array        $context
     * @param array        $attachments
     * @param bool         $ccEmails
     * @param bool         $bccEmails
     *
     * @return int
     */
    public function sendEmail(
        $templateName,
        $fromEmail,
        $toEmail,
        array $context = [],
        array $attachments = [],
        $ccEmails = false,
        $bccEmails = false
    ) {
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->parameters['from_email'][$fromEmail], $this->parameters['from_name'][$fromEmail])
            ->setTo($toEmail)
        ;

        foreach ($attachments as $attachment) {
            $message->attach($attachment);
        }

        if ($ccEmails && is_array($ccEmails)) {
            foreach ($ccEmails as $email => $name) {
                $message->addCc($email, $name);
            }
        }

        if ($bccEmails && is_array($bccEmails)) {
            foreach ($bccEmails as $email => $name) {
                $message->addBcc($email, $name);
            }
        }

        if (!empty($htmlBody)) {
            $message
                ->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain')
            ;
        } else {
            $message->setBody($textBody, 'text/plain');
        }

        return $this->mailer->send($message);
    }
}
