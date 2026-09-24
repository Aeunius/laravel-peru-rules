<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Enums\TipoDocumento;
use Aeunius\PeruRules\Support\AlfanumericoValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Pasaporte: hasta 12 letras o números.
 */
final class Pasaporte implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || ! AlfanumericoValidator::esValido($value, TipoDocumento::Pasaporte->longitudMaxima())) {
            $fail('peru-rules::validation.pasaporte')->translate();
        }
    }
}
