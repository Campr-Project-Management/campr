<?php

namespace AppBundle\Form\Currency;

use AppBundle\Entity\Currency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyChoiceType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => Currency::class,
                'choice_name' => function (Currency $currency) {
                    return sprintf('%s (%s)', $currency->getCode(), $currency->getName());
                },
                'placeholder' => 'placeholder.select_currency',
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return EntityType::class;
    }
}
