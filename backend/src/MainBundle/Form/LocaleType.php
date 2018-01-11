<?php

namespace MainBundle\Form;

use Lexik\Bundle\TranslationBundle\Manager\LocaleManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleType extends AbstractType
{
    private $localeManager;

    public function __construct(LocaleManagerInterface $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $locales = array_intersect_key(
            Intl::getLocaleBundle()->getLocaleNames(),
            array_flip($this->localeManager->getLocales())
        );

        $resolver->setDefaults(array(
            'choices' => array_flip($locales),
            'choice_translation_domain' => false,
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
