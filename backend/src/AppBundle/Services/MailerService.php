<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Component\Project\ProjectInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class MailerService
 * Service used for sending emails.
 */
class MailerService
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $options;

    /**
     * MailerService constructor.
     *
     * @param RouterInterface   $router
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     * @param array             $fromParameters
     * @param array             $options
     */
    public function __construct(
        RouterInterface $router,
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        array $fromParameters,
        array $options
    ) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameters = $fromParameters;
        $this->options = $options;
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

    public function sendRegistrationEmail(User $user)
    {
        $params = [
            'token' => $user->getActivationToken(),
            'full_name' => $user->getFullName(),
            'expiration_time' => $this->options['activation_token_expiration_number'],
        ];

        if (!empty($user->getPlainPassword())) {
            $params['plain_password'] = $user->getPlainPassword();
        }

        return $this
            ->sendEmail(
                'MainBundle:Email:user_register.html.twig',
                'info',
                $user->getEmail(),
                $params
            )
        ;
    }

    /**
     * @param User             $user
     * @param ProjectInterface $project
     *
     * @return int
     */
    public function sendProjectClonedEmail(User $user, ProjectInterface $project)
    {
        $baseUrl = $this->router->generate('app_homepage', [], Router::ABSOLUTE_URL);
        $params = [
            'url' => sprintf('%s#/projects/%d/dashboard', $baseUrl, $project->getId()),
            'full_name' => $user->getFullName(),
            'project_name' => $project->getName(),
        ];

        return $this
            ->sendEmail(
                'MainBundle:Email:project_cloned.html.twig',
                'info',
                $user->getEmail(),
                $params
            )
        ;
    }

    public function addFromParameter($name, $data)
    {
        $this->parameters['from_email'][$name] = isset($data['email']) ? $data['email'] : '';
        $this->parameters['from_name'][$name] = isset($data['name']) ? $data['name'] : '';
    }
}
