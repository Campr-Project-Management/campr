<?php

namespace MainBundle\Controller;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use AppBundle\Form\User\LoginType;
use AppBundle\Form\User\RegisterType;
use AppBundle\Form\User\ResetPasswordType;
use MainBundle\Form\ContactType;
use MainBundle\Form\User\HomepageRegisterType;
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
        $contactForm = $this->createForm(ContactType::class);

        if ($this->getUser() instanceof User) {
            $teams = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository(Team::class)
                ->findBy(['user' => $this->getUser()])
            ;
        } else {
            $teams = [];
        }

        return $this->render('MainBundle:Default:index.html.twig', [
            'teams' => $teams,
            'contactForm' => $contactForm->createView(),
        ]);
    }

    /**
     * @Route("/register", name="main_register", options={"expose"=true})
     * @Method({"GET","POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $contactForm = $this->createForm(ContactType::class);
        $signUpForm = $this->createForm(HomepageRegisterType::class, $user, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('main_register'),
        ]);

        $signUpForm->handleRequest($request);
        if ($request->isMethod(Request::METHOD_POST) && $signUpForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setPlainPassword(substr(md5(microtime()), rand(0, 26), 6));

            $em->persist($user);
            $em->flush();

            $this
                ->get('app.service.mailer')
                ->sendRegistrationEmail($user)
            ;

            $this->addFlash('registration_success', 'success.registration_success');

            return $this->redirectToRoute('main_homepage');
        }

        return $this->render('MainBundle:Default:register.html.twig', [
            'signUpForm' => $signUpForm->createView(),
            'contactForm' => $contactForm->createView(),
        ]);
    }

    /**
     * @Route("/register-form", name="main_register_form", options={"expose"=true})
     */
    public function registerFormAction()
    {
        $signUpForm = $this->createForm(HomepageRegisterType::class, new User(), [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('main_register'),
        ]);

        return $this->render('MainBundle:Default:_registration_form.html.twig', [
            'signUpForm' => $signUpForm->createView(),
        ]);
    }

    /**
     * Modules page.
     *
     * @Route("/modules", name="main_modules")
     *
     * @return Response|RedirectResponse
     */
    public function modulesAction(Request $request)
    {
        $contactForm = $this->createForm(ContactType::class);

        return $this->render('MainBundle:Default:modules.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }

    /**
     * About page.
     *
     * @Route("/about", name="main_about")
     *
     * @return Response|RedirectResponse
     */
    public function aboutAction(Request $request)
    {
        return $this->render('MainBundle:Default:about.html.twig');
    }

    /**
     * Imprint page.
     *
     * @Route("/imprint", name="main_imprint")
     *
     * @return Response|RedirectResponse
     */
    public function imprintAction(Request $request)
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
                        'contact_from',
                        $this->getParameter('email.contact_info'),
                        [
                            'email' => $data['email'],
                            'subject' => 'Campr contact',
                            'fullName' => $data['full_name'],
                            'message' => $data['message'],
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
                        'contact_from',
                        $this->getParameter('email.contact_info'),
                        [
                            'email' => $data['email'],
                            'subject' => 'Campr contact',
                            'fullName' => $data['full_name'],
                            'message' => $data['message'],
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
}
