<?php

namespace MainBundle\Controller\Admin;

use AppBundle\Entity\Payment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Payment admin controller.
 *
 * @Route("/admin/payment")
 */
class PaymentController extends Controller
{
    /**
     * List all Payment entities.
     *
     * @Route("/list", name="main_admin_payment_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $payments = $this
            ->getDoctrine()
            ->getRepository(Payment::class)
            ->findAll()
        ;

        return $this->render(
            'MainBundle:Admin/Payment:list.html.twig',
            [
                'payments' => $payments,
            ]
        );
    }

    /**
     * Refund payment.
     *
     * @Route("/{id}/refund", name="main_admin_payment_refund")
     * @Method({"GET"})
     *
     * @param Payment $payment
     *
     * @return Response
     */
    public function refundAction(Payment $payment)
    {
    }
}
