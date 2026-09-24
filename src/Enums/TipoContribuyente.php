<?php

namespace Aeunius\PeruRules\Enums;

/**
 * Tipo de contribuyente según el prefijo del RUC.
 */
enum TipoContribuyente
{
    /** Prefijo 10: persona natural; los 8 dígitos siguientes son su DNI. */
    case Natural;

    /** Prefijo 20: persona jurídica. */
    case Juridica;

    /** Prefijos 15, 16 y 17: casos especiales. */
    case Especial;

    public static function desdePrefijo(string $prefijo): ?self
    {
        return match ($prefijo) {
            '10' => self::Natural,
            '20' => self::Juridica,
            '15', '16', '17' => self::Especial,
            default => null,
        };
    }

    public function descripcion(): string
    {
        return match ($this) {
            self::Natural => 'Persona natural',
            self::Juridica => 'Persona jurídica',
            self::Especial => 'Caso especial',
        };
    }
}
