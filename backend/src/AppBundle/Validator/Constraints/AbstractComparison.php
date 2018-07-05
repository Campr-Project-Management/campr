<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Constraints\AbstractComparison as BaseAbstractComparison;

abstract class AbstractComparison extends BaseAbstractComparison
{
    /**
     * @var string
     */
    public $propertyPath;

    /**
     * AbstractComparison constructor.
     *
     * @param null $options
     */
    public function __construct($options = null)
    {
        if (null === $options) {
            $options = [];
        }
        if (is_array($options)) {
            if (!isset($options['value']) && !isset($options['propertyPath'])) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The "%s" constraint requires either the "value" or "propertyPath" option to be set.',
                        get_class($this)
                    )
                );
            }
            if (isset($options['value']) && isset($options['propertyPath'])) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The "%s" constraint requires only one of the "value" or "propertyPath" options to be set, not both.',
                        get_class($this)
                    )
                );
            }
            if (isset($options['propertyPath']) && !class_exists(PropertyAccess::class)) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'The "%s" constraint requires the Symfony PropertyAccess component to use the "propertyPath" option.',
                        get_class($this)
                    )
                );
            }
        }

        $options['value'] = '';

        parent::__construct($options);
    }
}
