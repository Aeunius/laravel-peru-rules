<?php

use Aeunius\PeruRules\Support\CciValidator;

it('acepta y normaliza CCI', function (string $cci, string $normalizado) {
    expect(CciValidator::esValido($cci))->toBeTrue()
        ->and(CciValidator::normalizar($cci))->toBe($normalizado);
})->with('ccis validos');

it('rechaza lo que no es un CCI', function (string $cci) {
    expect(CciValidator::esValido($cci))->toBeFalse()
        ->and(CciValidator::normalizar($cci))->toBeNull();
})->with('ccis invalidos');

it('calcula los dígitos de control', function (string $cci, string $digitos) {
    expect(CciValidator::digitosControl(substr($digitos, 0, 6), substr($digitos, 6, 12)))->toBe(substr($digitos, 18));
})->with('ccis validos');

it('sigue el ejemplo del README', function () {
    // entidad + oficina 002191: 0×1 + 0×2 + 2×1 + 1×2 + 9×1 + 1×2 = 15 → 5
    // cuenta 000123456789: 0+0+0+2+2+6+4+(1+0)+6+(1+4)+8+(1+8) = 43 → 7
    expect(CciValidator::digitosControl('002191', '000123456789'))->toBe('57');
});

it('acepta un solo par de dígitos de control', function (string $cci, string $digitos) {
    $validos = array_filter(
        range(0, 99),
        fn (int $control) => CciValidator::esValido(substr($digitos, 0, 18).sprintf('%02d', $control)),
    );

    expect($validos)->toHaveCount(1);
})->with('ccis validos');

it('exige las longitudes de cada parte', function (string $entidadOficina, string $cuenta) {
    CciValidator::digitosControl($entidadOficina, $cuenta);
})->with([
    ['00219', '000123456789'],
    ['002191', '00012345678'],
    ['00219A', '000123456789'],
])->throws(InvalidArgumentException::class);
