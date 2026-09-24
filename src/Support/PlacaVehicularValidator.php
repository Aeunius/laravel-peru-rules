<?php

namespace Aeunius\PeruRules\Support;

/**
 * Placa vehicular peruana.
 *
 * - Autos y demás vehículos: una letra, dos letras o dígitos y tres dígitos
 *   ("ABC-123", "A1B-234"), con o sin guion.
 * - Especiales, con prefijo E (Estado "EGA-123", policía "EPA-123",
 *   emergencias "EUA-123", diplomáticas "ECD-123"). Suelen escribirse con la E
 *   separada o en minúscula ("E GA-123", "eGA-123"); ambas formas se aceptan.
 * - Motos y mototaxis (categoría L): cuatro dígitos y dos caracteres
 *   ("2171-AY", "5040-6C"), o dos caracteres y cuatro dígitos ("C5-4481"). En
 *   los dos caracteres hay al menos una letra. La segunda forma exige el guion:
 *   sin él, "C54481" se lee como la placa de auto "C54-481".
 *
 * Todavía no cubre las placas de motos de siete caracteres que se entregan
 * desde diciembre de 2025.
 */
final class PlacaVehicularValidator
{
    /** Dos caracteres con al menos una letra. */
    private const PAR = '(?=[A-Z0-9]{0,1}[A-Z])[A-Z0-9]{2}';

    public static function esValido(string $placa): bool
    {
        return self::normalizar($placa) !== null;
    }

    /**
     * Devuelve la placa en mayúsculas y con guion ("ABC-123", "2171-AY",
     * "C5-4481"), o null si no es una placa válida.
     */
    public static function normalizar(string $placa): ?string
    {
        // "E GA-123" → "EGA-123": el espacio solo se admite tras la E de las especiales.
        $placa = preg_replace('/^E\s(?=[A-Z]{2})/', 'E', strtoupper(trim($placa))) ?? '';

        $formatos = [
            '/^([A-Z][A-Z0-9]{2})-?(\d{3})$/D',
            '/^(\d{4})-?('.self::PAR.')$/D',
            '/^('.self::PAR.')-(\d{4})$/D',
        ];

        foreach ($formatos as $formato) {
            if (preg_match($formato, $placa, $partes) === 1) {
                return $partes[1].'-'.$partes[2];
            }
        }

        return null;
    }
}
