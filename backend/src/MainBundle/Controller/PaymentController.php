<?php

namespace MainBundle\Controller;

use AppBundle\Entity\Payment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Payment controller.
 *
 * @Route("/payment")
 */
class PaymentController extends Controller
{
    /**
     * List all Payment entities.
     *
     * @Route("/list", name="main_payment_list")
     * @Method({"GET"})
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
            'MainBundle:Payment:list.html.twig',
            [
                'payments' => $payments,
            ]
        );
    }

    /**
     * Payment invoice.
     *
     * @Route("/{id}/invoice", name="main_payment_invoice")
     *
     * @return Response
     */
    public function invoiceAction($id)
    {
        return $this->render(
            'MainBundle:Payment:invoice.html.twig',
            [
                'payment' => $id,
            ]
        );
    }
}
