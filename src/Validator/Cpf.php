<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Cpf extends Constraint
{
    public string $message = 'O CPF "{{ value }}" não é válido.';
}
