<?php

namespace Aeunius\PeruRules\Support;

/**
 * Celular peruano: 9 dígitos que empiezan con 9.
 */
final class CelularValidator
{
    public static function esValido(string $celular): bool
    {
        return self::normalizar($celular) !== null;
    }

    /**
     * Devuelve los 9 dígitos, o null si no es un celular válido.
     *
     * Acepta el código de país (+51 o 51) y los separadores de uso común:
     * espacios, guiones, puntos y paréntesis. "+51 987 654 321",
     * "51987654321" y "987-654-321" dan "987654321".
     */
    public static function normalizar(string $celular): ?string
    {
        $digitos = preg_replace('/[\s\-.()]/', '', $celular) ?? '';

        if (preg_match('/^(?:\+?51)?(9\d{8})$/D', $digitos, $partes) !== 1) {
            return null;
        }

        return $partes[1];
    }
}
