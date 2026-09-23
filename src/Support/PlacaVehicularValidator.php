<?php

namespace Aeunius\PeruRules\Support;

/**
 * Placa vehicular del formato vigente: una letra, dos letras o dígitos y tres
 * dígitos ("ABC-123", "A1B-234"), con o sin guion.
 *
 * Incluye las placas especiales, que llevan el prefijo E (Estado "EGA-123",
 * policía "EPA-123", emergencias "EUA-123", diplomáticas "ECD-123"). Suelen
 * escribirse con la E separada o en minúscula ("E GA-123", "eGA-123"), y ambas
 * formas se aceptan.
 *
 * Todavía no cubre las placas de motos.
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
        // "E GA-123" → "EGA-123": el espacio solo se admite tras la E de las especiales.
        $placa = preg_replace('/^E\s(?=[A-Z]{2})/', 'E', strtoupper(trim($placa))) ?? '';

        if (preg_match('/^([A-Z][A-Z0-9]{2})-?(\d{3})$/', $placa, $partes) !== 1) {
            return null;
        }

        return $partes[1].'-'.$partes[2];
    }
}
