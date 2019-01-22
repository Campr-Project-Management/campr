<?php

namespace AppBundle\Form\User;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\User;

class UserThemeChoiceType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'choices' => $this->getChoices(),
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @return array
     */
    private function getChoices(): array
    {
        return [
            'label.theme.dark' => User::THEME_DARK,
            'label.theme.light' => User::THEME_LIGHT,
        ];
    }
}
