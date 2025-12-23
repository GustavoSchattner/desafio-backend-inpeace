<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CpfValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Cpf) {
            throw new UnexpectedTypeException($constraint, Cpf::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $cpf = preg_replace('/\D/', '', (string) $value);

        if (11 !== strlen($cpf) || preg_match('/(\d)\1{10}/', $cpf)) {
            $this->addViolation($value, $constraint);
            return;
        }

        for ($t = 9; $t < 11; ++$t) {
            for ($d = 0, $c = 0; $c < $t; ++$c) {
                $d += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $cpf[$t] !== $d) {
                $this->addViolation($value, $constraint);
                return;
            }
        }
    }

    /**
     * @param mixed $value
     * @param Cpf $constraint
     * @return void
     */
    private function addViolation(mixed $value, Cpf $constraint): void
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', (string) $value)
            ->addViolation();
    }
}
