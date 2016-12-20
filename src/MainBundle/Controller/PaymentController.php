<?php

namespace MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * Payment methods list.
     *
     * @Route("/method/list", name="main_payment_method_list")
     *
     * @return Response
     */
    public function methodListAction()
    {
        return $this->render('MainBundle:Payment:method_list.html.twig');
    }

    /**
     * Payment list.
     *
     * @Route("/list", name="main_payment_list")
     *
     * @return Response
     */
    public function listAction()
    {
        return $this->render('MainBundle:Payment:list.html.twig');
    }

    /**
     * Create new payment.
     *
     * @Route("/create", name="main_payment_create")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->render('MainBundle:Payment:create.html.twig');
    }

    /**
     * Create new team.
     *
     * @Route("/{id}/edit", name="main_payment_edit")
     *
     * @return Response
     */
    public function editAction($id)
    {
        return $this->render(
            'MainBundle:Payment:edit.html.twig',
            [
                'payment' => $id,
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
