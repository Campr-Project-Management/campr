<?php


namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Todo;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TodoValidator extends ConstraintValidator
{
    /**
     * @Annotation
     * @param Todo                          $td         The value that should be validated
     * @param TodoValidator|Constraint $constraint The constraint for the validation
     */

    public function validate($td, Constraint $constraint)
    {
       dump($td);
       die('td');
    }
}
