<?php

namespace AppBundle\Validator\Constraints;

use Component\Resource\Model\SequenceableInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SequenceValidator extends ConstraintValidator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * SequenceValidator constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param object              $entity
     * @param Sequence|Constraint $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof Sequence) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Sequence');
        }

        if (!$entity instanceof SequenceableInterface) {
            throw new UnexpectedTypeException($entity, SequenceableInterface::class);
        }

        if ($entity->getSequence() < 0) {
            $this
                ->context
                ->buildViolation($constraint->messageNegative)
                ->atPath('sequence')
                ->addViolation()
            ;
        }

        $existingObject = $this
            ->em
            ->getRepository(get_class($entity))
            ->findOneBy([
                'sequence' => $entity->getSequence(),
            ])
        ;

        if (
            $existingObject
            && (
                $existingObject->getId() !== $entity->getId()
                || !$entity->getId()
            )
        ) {
            $this
                ->context
                ->buildViolation($constraint->messageUnique)
                ->atPath('sequence')
                ->addViolation()
            ;
        }
    }
}
