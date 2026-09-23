<?php

namespace Aeunius\PeruRules\Support;

/**
 * Documentos que el catálogo 06 de la SUNAT solo define como "alfanumérico de
 * hasta N caracteres": carné de extranjería, pasaporte, cédula diplomática y
 * documento tributario de no domiciliado.
 */
final class AlfanumericoValidator
{
    public static function isValid(string $numero, int $maximo): bool
    {
        return preg_match('/^[A-Za-z0-9]{1,'.$maximo.'}$/', $numero) === 1;
    }
}
