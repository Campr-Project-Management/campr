<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\MailerService;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class MailerServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var MailerService */
    private $mailer;

    /** @var RouterInterface */
    private $router;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $swiftMailer;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $twig;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $template;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->swiftMailer = $this
            ->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->router = $this
            ->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->twig = $this
            ->getMockBuilder(\Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->template = $this
            ->getMockBuilder(\Twig_Template::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this
            ->template
            ->expects($this->any())
            ->method('renderBlock')
            ->willReturnMap([
                ['subject', [], [], true, 'subject'],
                ['body_text', [], [], true, 'body_text'],
                ['body_html', [], [], true, 'body_html'],
            ])
        ;

        $this->mailer = new MailerService(
            $this->router,
            $this->swiftMailer,
            $this->twig,
            [
                'from_email' => [
                    'default' => 'info@campr.biz',
                ],
                'from_name' => [
                    'default' => 'Info Campr',
                ],
            ],
            [
                'activation_token_expiration_number' => 1,
            ]
        );
    }

    /**
     * @dataProvider getDataForTestSendEmail
     *
     * @param string $templateName
     * @param string $toEmail
     */
    public function testSendEmail($templateName, $toEmail)
    {
        $this
            ->twig
            ->expects($this->once())
            ->method('loadTemplate')
            ->with($templateName)
            ->willReturn($this->template)
        ;

        $that = $this;
        $this
            ->swiftMailer
            ->expects($this->once())
            ->method('send')
            ->willReturnCallback(function (\Swift_Message $message) use ($that, $toEmail) {
                $that->assertInstanceOf(\Swift_Message::class, $message);
                $that->assertSame('subject', $message->getSubject());
                $that->assertSame('body_html', $message->getBody());
                $that->assertSame(
                    [$toEmail => null],
                    $message->getTo()
                );
                $that->assertSame(
                    ['notification@campr.biz' => 'Notification Campr'],
                    $message->getFrom()
                );
            })
        ;

        $this->mailer->sendEmail($templateName, 'notification', $toEmail);
    }

    /**
     * @return array
     */
    public function getDataForTestSendEmail()
    {
        return [
            ['templateName', 'to@email.com'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->swiftMailer = null;
        $this->twig = null;
        $this->template = null;
        $this->mailer = null;
    }
}
