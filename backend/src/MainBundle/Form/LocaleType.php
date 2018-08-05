<?php

namespace MainBundle\Form;

use Component\Locale\Provider\LocaleProviderInterface;
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
    private $localeProvider;

    /**
     * LocaleType constructor.
     *
     * @param LocaleProviderInterface $localeProvider
     */
    public function __construct(LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'choices' => $this->getChoices(),
                'choice_translation_domain' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return array
     */
    private function getChoices(): array
    {
        $choices = [];
        foreach ($this->localeProvider->getAvailableLocalesCodes() as $localeCode) {
            $name = Intl::getLocaleBundle()->getLocaleName($localeCode);
            $choices[$name] = $localeCode;
        }

        return $choices;
    }
}
