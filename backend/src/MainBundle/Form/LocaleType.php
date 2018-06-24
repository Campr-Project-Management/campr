<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LocaleType.
 */
class LocaleType extends AbstractType
{
    /**
     * @var string[]
     */
    private $locales;

    /**
     * LocaleType constructor.
     *
     * @param string[] $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $locales = array_intersect_key(
            Intl::getLocaleBundle()->getLocaleNames(),
            array_flip($this->locales)
        );

        $resolver->setDefaults(array(
            'choices' => array_flip($locales),
            'choice_translation_domain' => false,
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
