<?php

namespace AppBundle\Form\Project;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileSizeChoices extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->getChoices(),
            ''
        ]);
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
        $oneMB = 1024 * 1024;
        $choices = [];
        foreach (range($oneMB * 10, $oneMB * 100, $oneMB * 10) as $value) {
            $choices[sprintf('%d MB', ($value / $oneMB))] = $value;
        }

        return $choices;
    }
}
