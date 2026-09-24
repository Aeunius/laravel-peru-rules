<?php

use Aeunius\PeruRules\Enums\TipoDocumento;
use Aeunius\PeruRules\Support\AlfanumericoValidator;

it('usa los códigos del catálogo 06 de la SUNAT', function () {
    expect(array_map(fn (TipoDocumento $tipo) => $tipo->value, TipoDocumento::cases()))
        ->toBe(['0', '1', '4', '6', '7', 'A']);
});

it('valida el número según el tipo', function (TipoDocumento $tipo, string $valido, string $invalido) {
    expect($tipo->esValido($valido))->toBeTrue()
        ->and($tipo->esValido($invalido))->toBeFalse();
})->with([
    'DNI' => [TipoDocumento::Dni, '12345678', '1234567'],
    'RUC' => [TipoDocumento::Ruc, '20131312955', '20131312956'],
    'carné de extranjería' => [TipoDocumento::CarneExtranjeria, '001234567', '0012345678901'],
    'pasaporte' => [TipoDocumento::Pasaporte, 'AB1234567', 'AB-1234567'],
    'no domiciliado' => [TipoDocumento::NoDomiciliadoSinRuc, '123456789012345', '1234567890123456'],
    'cédula diplomática' => [TipoDocumento::CedulaDiplomatica, 'CD12345', 'CD 12345'],
]);

it('limita los alfanuméricos a su longitud máxima', function () {
    expect(AlfanumericoValidator::esValido(str_repeat('A', 12), 12))->toBeTrue()
        ->and(AlfanumericoValidator::esValido(str_repeat('A', 13), 12))->toBeFalse()
        ->and(AlfanumericoValidator::esValido('', 12))->toBeFalse();
});
