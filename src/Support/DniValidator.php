<?php

namespace Aeunius\PeruRules\Support;

/**
 * Validación del número de DNI de la RENIEC: 8 dígitos.
 */
final class DniValidator
{
    public static function isValid(string $dni): bool
    {
        return preg_match('/^\d{8}$/', $dni) === 1;
    }
}
