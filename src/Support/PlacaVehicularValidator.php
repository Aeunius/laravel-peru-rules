<?php

namespace Aeunius\PeruRules\Support;

/**
 * Placa vehicular del formato vigente: una letra, dos letras o dígitos y tres
 * dígitos ("ABC-123", "A1B-234"), con o sin guion.
 *
 * Todavía no cubre las placas de motos ni las especiales.
 */
final class PlacaVehicularValidator
{
    public static function isValid(string $placa): bool
    {
        return self::normalizar($placa) !== null;
    }

    /**
     * Devuelve la placa en mayúsculas y con guion ("ABC-123"), o null si no es
     * una placa válida.
     */
    public static function normalizar(string $placa): ?string
    {
        if (preg_match('/^([A-Z][A-Z0-9]{2})-?(\d{3})$/', strtoupper(trim($placa)), $partes) !== 1) {
            return null;
        }

        return $partes[1].'-'.$partes[2];
    }
}
