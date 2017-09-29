<?php

namespace AppBundle\Form\Cost;

use AppBundle\Entity\Cost;
use AppBundle\Entity\Project;
use AppBundle\Entity\Resource;
use AppBundle\Entity\Unit;
use AppBundle\Entity\WorkPackage;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'required' => false,
                'class' => Project::class,
                'choice_label' => 'name',
            ])
            ->add('workPackage', EntityType::class, [
                'class' => WorkPackage::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('resource', EntityType::class, [
                'class' => Resource::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('name', TextType::class, ['required' => false])
            ->add('type', IntegerType::class)
            ->add('expenseType', IntegerType::class)
            ->add('rate', NumberType::class)
            ->add('quantity', NumberType::class)
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('duration', TextType::class, ['required' => false])
            ->add('customUnit', TextType::class, ['mapped' => false])
        ;

        $builder
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $cost = $form->getData();
                    $customValue = $form->get('customUnit')->getData();
                    if ($customValue) {
                        $repo = $this->em->getRepository(Unit::class);
                        $maxSequence = $repo->findMaxSequence();
                        $customUnit = $repo->findOneByName($customValue);
                        if (!$customUnit) {
                            $customUnit = new Unit();
                            $customUnit->setName($customValue);
                            $customUnit->setSequence($maxSequence + 1);
                            $this->em->persist($customUnit);
                            $this->em->flush();
                        }
                        $cost->setUnit($customUnit);
                    }
                }
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cost::class,
            'csrf_protection' => false,
        ]);
    }
}
