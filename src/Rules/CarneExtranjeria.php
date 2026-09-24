<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Enums\TipoDocumento;
use Aeunius\PeruRules\Support\AlfanumericoValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Carné de extranjería: hasta 12 letras o números.
 */
final class CarneExtranjeria implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || ! AlfanumericoValidator::esValido($value, TipoDocumento::CarneExtranjeria->longitudMaxima())) {
            $fail('peru-rules::validation.carne_extranjeria')->translate();
        }
    }
}
