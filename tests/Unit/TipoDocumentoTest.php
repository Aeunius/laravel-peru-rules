<?php

use Aeunius\PeruRules\Enums\TipoDocumento;
use Aeunius\PeruRules\Support\AlfanumericoValidator;

it('usa los códigos del catálogo 06 de la SUNAT', function () {
    expect(array_map(fn (TipoDocumento $tipo) => $tipo->value, TipoDocumento::cases()))
        ->toBe(['0', '1', '4', '6', '7', 'A']);
});

it('valida el número según el tipo', function (string $codigo, string $valido, string $invalido) {
    $tipo = TipoDocumento::from($codigo);

    expect($tipo->esValido($valido))->toBeTrue()
        ->and($tipo->esValido($invalido))->toBeFalse();
})->with('tipos de documento');

it('limita los alfanuméricos a su longitud máxima', function () {
    expect(AlfanumericoValidator::esValido(str_repeat('A', 12), 12))->toBeTrue()
        ->and(AlfanumericoValidator::esValido(str_repeat('A', 13), 12))->toBeFalse()
        ->and(AlfanumericoValidator::esValido('', 12))->toBeFalse();
});
