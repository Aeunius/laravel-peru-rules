<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Support\DniValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * DNI de 8 dígitos. Acepta texto o entero; un DNI con ceros a la izquierda solo
 * llega completo como texto.
 */
final class Dni implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || ! DniValidator::esValido($value)) {
            $fail('peru-rules::validation.dni')->translate();
        }
    }
}
