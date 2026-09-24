<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Support\CelularValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Celular de 9 dígitos que empieza con 9. Acepta el código de país y los
 * separadores habituales; para guardar solo los 9 dígitos, usa
 * CelularValidator::normalizar().
 */
final class Celular implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || ! CelularValidator::esValido($value)) {
            $fail('peru-rules::validation.celular')->translate();
        }
    }
}
