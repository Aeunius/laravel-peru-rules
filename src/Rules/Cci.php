<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Support\CciValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * CCI de 20 dígitos con dígitos de control válidos. Acepta espacios y guiones;
 * para guardar solo los dígitos, usa CciValidator::normalizar().
 */
final class Cci implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! CciValidator::esValido($value)) {
            $fail('peru-rules::validation.cci')->translate();
        }
    }
}
