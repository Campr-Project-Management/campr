<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\AbstractComparison;
use Symfony\Component\Validator\Constraints\GreaterThanValidator as BaseGreaterThanValidator;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class GreaterThanValidator extends BaseGreaterThanValidator
{
    private $propertyAccessor;

    /**
     * GreaterThanValidator constructor.
     *
     * @param PropertyAccessor|null $propertyAccessor
     */
    public function __construct(PropertyAccessor $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param mixed                  $value
     * @param Constraint|GreaterThan $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AbstractComparison) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AbstractComparison');
        }

        if (null === $value) {
            return;
        }

        if ($path = $constraint->propertyPath) {
            if (null === $object = $this->context->getObject()) {
                return;
            }
            try {
                $comparedValue = $this->getPropertyAccessor()->getValue($object, $path);
            } catch (NoSuchPropertyException $e) {
                throw new ConstraintDefinitionException(
                    sprintf(
                        'Invalid property path "%s" provided to "%s" constraint: %s',
                        $path,
                        get_class($constraint),
                        $e->getMessage()
                    ), 0, $e
                );
            }
        } else {
            $comparedValue = $constraint->value;
        }

        if (is_string($comparedValue)) {
            if ($value instanceof \DateTimeImmutable) {
                $comparedValue = new \DateTimeImmutable($comparedValue);
            } elseif ($value instanceof \DateTimeInterface) {
                $comparedValue = new \DateTime($comparedValue);
            }
        }

        if (!$this->compareValues($value, $comparedValue)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value, self::OBJECT_TO_STRING | self::PRETTY_DATE))
                ->setParameter(
                    '{{ compared_value }}',
                    $this->formatValue($comparedValue, self::OBJECT_TO_STRING | self::PRETTY_DATE)
                )
                ->setParameter('{{ compared_value_type }}', $this->formatTypeOf($comparedValue))
                ->setCode($this->getErrorCode())
                ->addViolation();
        }
    }

    /**
     * @return null|PropertyAccessor
     */
    private function getPropertyAccessor()
    {
        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
