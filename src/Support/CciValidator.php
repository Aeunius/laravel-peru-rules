<?php

namespace Aeunius\PeruRules\Support;

/**
 * Código de Cuenta Interbancario (CCI): 20 dígitos.
 *
 *   entidad (3) + oficina (3) + cuenta (12) + control (2)
 *
 * El primer dígito de control verifica entidad + oficina, y el segundo, la
 * cuenta. Solo se comprueban el formato y los dígitos de control: no se
 * consulta si la cuenta existe ni si el código de entidad está asignado.
 */
final class CciValidator
{
    public static function esValido(string $cci): bool
    {
        return self::normalizar($cci) !== null;
    }

    /**
     * Devuelve los 20 dígitos, o null si no es un CCI válido. Acepta espacios y
     * guiones: "002-540-102683583013-38" da "00254010268358301338".
     */
    public static function normalizar(string $cci): ?string
    {
        $cci = preg_replace('/[\s\-]/', '', $cci) ?? '';

        if (preg_match('/^\d{20}$/D', $cci) !== 1) {
            return null;
        }

        $control = self::digitosControl(substr($cci, 0, 6), substr($cci, 6, 12));

        return substr($cci, 18) === $control ? $cci : null;
    }

    /**
     * Calcula los dos dígitos de control.
     *
     * @param  string  $entidadOficina  6 dígitos: entidad (3) + oficina (3).
     * @param  string  $cuenta  12 dígitos.
     *
     * @throws \InvalidArgumentException si las longitudes no son las esperadas.
     */
    public static function digitosControl(string $entidadOficina, string $cuenta): string
    {
        if (preg_match('/^\d{6}$/D', $entidadOficina) !== 1 || preg_match('/^\d{12}$/D', $cuenta) !== 1) {
            throw new \InvalidArgumentException('Se esperaban 6 dígitos de entidad y oficina, y 12 de cuenta.');
        }

        return self::digito($entidadOficina).self::digito($cuenta);
    }

    /**
     * Pesos 1 y 2 alternados desde la izquierda; de cada producto se suman sus
     * dígitos (14 → 1 + 4). El dígito es lo que falta para la siguiente decena.
     */
    private static function digito(string $numero): int
    {
        $suma = 0;

        foreach (str_split($numero) as $i => $digito) {
            $producto = (int) $digito * ($i % 2 === 0 ? 1 : 2);
            $suma += intdiv($producto, 10) + $producto % 10;
        }

        return (10 - $suma % 10) % 10;
    }
}
