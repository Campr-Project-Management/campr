<?php

namespace MainBundle\Controller\API;

use MainBundle\Controller\BaseController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class ApiController extends BaseController
{
    protected function getFormErrors(Form $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $childForm) {
            if ($childForm instanceof Form) {
                $childErrors = self::getFormErrors($childForm);
                if (count($childErrors)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    protected function processForm(Request $request, FormInterface $form, $clearMissing = true)
    {
        $data = $request->request->all();
        if (null === $data) {
            throw new BadRequestHttpException();
        }

        $form->submit($data, $clearMissing);
    }
}
