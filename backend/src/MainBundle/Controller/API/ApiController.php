<?php

namespace MainBundle\Controller\API;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use MainBundle\Controller\BaseController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class ApiController extends BaseController
{
    /**
     * MAKE SURE TO USE static WHEN REFERENCING THESE!!!
     *
     * Read about late static binding if you have questions.
     */
    const ENTITY_CLASS = '';
    const FORM_CLASS = '';

    /**
     * @return ObjectManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        $this->assertClassExists(static::ENTITY_CLASS);

        return $this
            ->getDoctrine()
            ->getRepository(static::ENTITY_CLASS)
        ;
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return Form
     */
    protected function getForm($data = null, array $options = array())
    {
        $this->assertClassExists(static::FORM_CLASS);

        return $this->createForm(static::FORM_CLASS, $data, $options);
    }

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
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->get('event_dispatcher');
    }

    /**
     * @param string $name
     * @param Event  $event
     *
     * @return Event
     */
    protected function dispatchEvent(string $name, Event $event): Event
    {
        return $this->getEventDispatcher()->dispatch($name, $event);
    }
}
