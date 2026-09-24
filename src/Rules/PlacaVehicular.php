<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Support\PlacaVehicularValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Placa vehicular del formato vigente ("ABC-123"), con o sin guion. Para
 * guardarla siempre igual, usa PlacaVehicularValidator::normalizar().
 */
final class PlacaVehicular implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! PlacaVehicularValidator::esValido($value)) {
            $fail('peru-rules::validation.placa_vehicular')->translate();
        }
    }
}
