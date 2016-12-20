<?php

namespace MainBundle\Controller\Admin;

use AppBundle\Entity\PaymentMethod;
use MainBundle\Form\PaymentMethod\CreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PaymentMethod admin controller.
 *
 * @Route("/admin/payment-method")
 */
class PaymentMethodController extends Controller
{
    /**
     * List all PaymentMethod entities.
     *
     * @Route("/list", name="main_admin_payment_method_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $paymentMethods = $this
            ->getDoctrine()
            ->getRepository(PaymentMethod::class)
            ->findAll()
        ;

        return $this->render(
            'MainBundle:Admin/PaymentMethod:list.html.twig',
            [
                'payment_methods' => $paymentMethods,
            ]
        );
    }

    /**
     * Creates a new PaymentMethod entity.
     *
     * @Route("/create", name="main_admin_payment_method_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentMethod = $form->getData();
            $em->persist($paymentMethod);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.payment_method.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('main_admin_payment_method_list');
        }

        return $this->render(
            'MainBundle:Admin/PaymentMethod:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing PaymentMethod entity.
     *
     * @Route("/{id}/edit", name="main_admin_payment_method_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request       $request
     * @param PaymentMethod $paymentMethod
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, PaymentMethod $paymentMethod)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class, $paymentMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($paymentMethod);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.payment_method.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('main_admin_payment_method_list');
        }

        return $this->render(
            'MainBundle:Admin/PaymentMethod:edit.html.twig',
            [
                'payment_method' => $paymentMethod,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific PaymentMethod entity.
     *
     * @Route("/{id}/delete", name="main_admin_payment_method_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param PaymentMethod $paymentMethod
     *
     * @return Response
     */
    public function deleteAction(PaymentMethod $paymentMethod)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($paymentMethod);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.payment_method.delete.success', [], 'admin')
            )
        ;

        return $this->redirectToRoute('main_admin_payment_method_list');
    }
}
