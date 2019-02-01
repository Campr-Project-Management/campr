<?php

namespace Component\Settings\Form\Type;

use Component\Settings\SchemaRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsType extends AbstractType
{
    /**
     * @var SchemaRegistryInterface
     */
    private $schemaRegistry;

    /**
     * SettingsType constructor.
     *
     * @param SchemaRegistryInterface $schemaRegistry
     */
    public function __construct(SchemaRegistryInterface $schemaRegistry)
    {
        $this->schemaRegistry = $schemaRegistry;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $schema = $this->schemaRegistry->get($options['schema']);

        $schema->buildForm($builder);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $names = array_keys($this->schemaRegistry->all());

        $resolver
            ->setDefined(['schema'])
            ->setRequired(['schema'])
            ->setAllowedTypes('schema', ['string'])
            ->setAllowedValues('schema', $names);
    }
}
