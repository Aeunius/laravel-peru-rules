<?php

namespace Aeunius\PeruRules\Enums;

use Aeunius\PeruRules\Rules\CarneExtranjeria;
use Aeunius\PeruRules\Rules\Dni;
use Aeunius\PeruRules\Rules\DocumentoIdentidad;
use Aeunius\PeruRules\Rules\Pasaporte;
use Aeunius\PeruRules\Rules\Ruc;
use Aeunius\PeruRules\Support\AlfanumericoValidator;
use Aeunius\PeruRules\Support\DniValidator;
use Aeunius\PeruRules\Support\RucValidator;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Tipos de documento de identidad del catálogo 06 de la SUNAT, con los mismos
 * códigos que usa la facturación electrónica.
 */
enum TipoDocumento: string
{
    case NoDomiciliadoSinRuc = '0';
    case Dni = '1';
    case CarneExtranjeria = '4';
    case Ruc = '6';
    case Pasaporte = '7';
    case CedulaDiplomatica = 'A';

    public function descripcion(): string
    {
        return match ($this) {
            self::NoDomiciliadoSinRuc => 'Documento tributario de no domiciliado sin RUC',
            self::Dni => 'DNI',
            self::CarneExtranjeria => 'Carné de extranjería',
            self::Ruc => 'RUC',
            self::Pasaporte => 'Pasaporte',
            self::CedulaDiplomatica => 'Cédula diplomática de identidad',
        };
    }

    /**
     * Longitud máxima del número según el catálogo 06.
     */
    public function longitudMaxima(): int
    {
        return match ($this) {
            self::Dni => 8,
            self::Ruc => 11,
            self::CarneExtranjeria, self::Pasaporte => 12,
            self::NoDomiciliadoSinRuc, self::CedulaDiplomatica => 15,
        };
    }

    public function isValid(string $numero): bool
    {
        return match ($this) {
            self::Dni => DniValidator::isValid($numero),
            self::Ruc => RucValidator::isValid($numero),
            default => AlfanumericoValidator::isValid($numero, $this->longitudMaxima()),
        };
    }

    public function regla(): ValidationRule
    {
        return match ($this) {
            self::Dni => new Dni,
            self::Ruc => new Ruc,
            self::CarneExtranjeria => new CarneExtranjeria,
            self::Pasaporte => new Pasaporte,
            default => DocumentoIdentidad::de($this),
        };
    }
}
