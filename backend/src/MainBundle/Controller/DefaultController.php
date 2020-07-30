<?php

namespace MainBundle\Controller;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use AppBundle\Form\User\LoginType;
use AppBundle\Form\User\RegisterType;
use AppBundle\Form\User\ResetPasswordType;
use MainBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main_homepage", options={"expose"=true})
     */
    public function indexAction()
    {
        if (!($this->getUser() instanceof User)) {
            return $this->redirectToRoute('main_login');
        }

        $teams = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Team::class)
            ->findBy(['user' => $this->getUser()])
        ;

        return $this->render('MainBundle:Default:index.html.twig', [
            'teams' => $teams,
            'domain' => $this->getParameter('domain')
        ]);
    }

    /**
     * Imprint page.
     *
     * @Route("/imprint", name="main_imprint")
     *
     * @return Response|RedirectResponse
     */
    public function imprintAction()
    {
        return $this->render('MainBundle:Default:imprint.html.twig');
    }

    /**
     * Contact page.
     *
     * @Route("/contact", name="main_contact")
     * @Method({"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $mailerService = $this->get('app.service.mailer');
                $mailerService->addFromParameter(
                    'contact_from',
                    [
                        'email' => $data['email'],
                        'name' => $data['full_name'], ]
                );
                $mailerService
                    ->sendEmail(
                        'MainBundle:Email:contact.html.twig',
                        'notification',
                        $this->getParameter('email.contact_info'),
                        [
                            'email' => $data['email'],
                            'subject' => 'Campr contact',
                            'fullName' => $data['full_name'],
                            'message' => $data['message'],
                        ],
                        [],
                        [],
                        [
                            $data['email'] => $data['full_name'],
                        ]
                    )
                ;

                $this
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'success',
                        $this
                            ->get('translator')
                            ->trans('success.contact.message.sent', [], 'flashes')
                    )
                ;

                return $this->redirectToRoute('main_contact');
            } else {
                $this
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'error',
                        $this
                            ->get('translator')
                            ->trans('success.contact.message.not_sent', [], 'flashes')
                    )
                ;
            }
        }

        return $this->render(
            'MainBundle:Default:contact.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Contact Email.
     *
     * @Route("/contact-email", name="main_contact_email", options={"expose"= true})
     * @Method({"GET","POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function contactEmailAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $this->processForm($request, $form);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $mailerService = $this->get('app.service.mailer');
                $mailerService->addFromParameter(
                    'contact_from',
                    [
                        'email' => $data['email'],
                        'name' => $data['full_name'],
                    ]
                );
                $mailerService
                    ->sendEmail(
                        'MainBundle:Email:contact.html.twig',
                        'notification',
                        $this->getParameter('email.contact_info'),
                        [
                            'email' => $data['email'],
                            'subject' => 'Campr contact',
                            'fullName' => $data['full_name'],
                            'message' => $data['message'],
                        ],
                        [],
                        [],
                        [
                            $data['email'] => $data['full_name'],
                        ]
                    )
                ;

                return new JsonResponse([
                    'ok' => true,
                    'message' => $this->get('translator')->trans('success.contact.message.sent', [], 'flashes'),
                ]);
            }
        }

        return new JsonResponse([
            'ok' => false,
            'message' => $this->get('translator')->trans('success.contact.message.not_sent', [], 'flashes'),
            'errors' => $this->getFormErrors($form),
        ]);
    }

    /**
     * Login into application page.
     *
     * @Route("/login", name="main_login")
     *
     * @return Response|RedirectResponse
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class);
        $formRegister = $this->createForm(RegisterType::class);
        $formReset = $this->createForm(ResetPasswordType::class);

        return $this->render(
            'MainBundle:Default:login.html.twig',
            [
                'last_username' => $lastUsername,
                'login' => true,
                'error' => $error,
                'form' => $form->createView(),
                'form_register' => $formRegister->createView(),
                'form_reset' => $formReset->createView(),
                'route' => 'main_login',
                'domain' => $this->getParameter('domain'),
            ]
        );
    }

    /**
     * Logout from application.
     *
     * @Route("/logout", name="main_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param bool          $clearMissing
     */
    protected function processForm(Request $request, FormInterface $form, $clearMissing = true)
    {
        $data = $request->request->all();
        if (null === $data) {
            throw new BadRequestHttpException();
        }

        if ($request->files->count()) {
            $data = array_merge_recursive($data, $request->files->all());
        }

        $form->submit($data, $clearMissing);
    }

    /**
     * @param Form $form
     *
     * @return array
     */
    protected function getFormErrors(Form $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $childForm) {
            if ($childForm instanceof Form) {
                $childErrors = $this->getFormErrors($childForm);
                if (count($childErrors)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    /**
     * @Route("/send-code-via-email", name="main_send_code_via_mail")
     */
    public function sendCodeViaEmailAction(Request $request)
    {
        $this->mustBeIn2FA($request);

        $this->container->get('app.service.mailer')->sendCodeViaEmail($this->getUser());

        $this->addFlash('two_factor', 'two_factor.send_code_via_email.email_success');

        return $this->redirectToRoute('main_homepage');
    }

    /**
     * @Route("/remove-two-factor-auth", name="main_remove_two_factor")
     */
    public function removeTwoFactorAction(Request $request)
    {
        $this->mustBeIn2FA($request);

        $this->container->get('app.service.mailer')->sendGoogleAuthenticatorRemoveEmail($this->getUser());

        $this->addFlash('two_factor', 'two_factor.remove.email_success');

        return $this->redirectToRoute('main_homepage');
    }

    /**
     * @Route("/remove-two-factor-auth/{code}", name="main_remove_two_factor_code")
     */
    public function removeTwoFactorCodeAction(Request $request, string $code)
    {
        $this->mustBeIn2FA($request);

        if (!empty($this->getUser()->getGoogleAuthenticatorSecret())) {
            $verify = sha1($this->getUser()->getGoogleAuthenticatorSecret());
        } else {
            $verify = null;
        }

        if ($verify !== $code) {
            throw $this->createAccessDeniedException();
        }

        $this->getUser()->setGoogleAuthenticatorSecret(null);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($this->getUser());
        $manager->flush();

        $this->addFlash('two_factor', 'two_factor.remove.success');

        return $this->redirectToRoute('main_logout');
    }

    /**
     * @param Request $request
     */
    private function mustBeIn2FA(Request $request)
    {
        if (!($this->getUser() instanceof User) || empty($this->getUser()->getGoogleAuthenticatorSecret())) {
            throw $this->createAccessDeniedException();
        }

        $keys = array_keys($request->getSession()->all());

        foreach ($keys as $key) {
            if (0 === strpos($key, 'two_factor_')) {
                return;
            }
        }

        throw $this->createAccessDeniedException();
    }
}
