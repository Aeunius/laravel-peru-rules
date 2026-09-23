<?php

namespace Aeunius\PeruRules\Support;

/**
 * Validación del RUC (Registro Único de Contribuyentes) de la SUNAT.
 *
 * Solo comprueba formato, prefijo y dígito verificador: no consulta si el RUC
 * existe o está activo.
 */
final class RucValidator
{
    /** Persona natural: el prefijo 10 seguido del DNI. */
    public const NATURAL = '10';

    /** Persona jurídica. */
    public const JURIDICA = '20';

    /** @var list<string> */
    public const PREFIJOS = ['10', '15', '16', '17', '20'];

    /** @var list<int> */
    private const PESOS = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];

    /**
     * @param  list<string>  $prefijos  Prefijos aceptados; por defecto, todos los válidos.
     */
    public static function isValid(string $ruc, array $prefijos = self::PREFIJOS): bool
    {
        if (preg_match('/^\d{11}$/', $ruc) !== 1) {
            return false;
        }

        if (! in_array(substr($ruc, 0, 2), $prefijos, true)) {
            return false;
        }

        return self::digitoVerificador(substr($ruc, 0, 10)) === (int) $ruc[10];
    }

    /**
     * Calcula el dígito verificador a partir de los 10 primeros dígitos:
     * r = 11 − (Σ dígito × peso) mod 11, con 10 → 0 y 11 → 1.
     *
     * @throws \InvalidArgumentException si no recibe exactamente 10 dígitos.
     */
    public static function digitoVerificador(string $base): int
    {
        if (preg_match('/^\d{10}$/', $base) !== 1) {
            throw new \InvalidArgumentException('Se esperaban los 10 primeros dígitos del RUC.');
        }

        $suma = 0;

        foreach (self::PESOS as $i => $peso) {
            $suma += (int) $base[$i] * $peso;
        }

        return (11 - $suma % 11) % 10;
    }
}
