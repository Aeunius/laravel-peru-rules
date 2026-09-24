<?php

use Aeunius\PeruRules\Support\CelularValidator;

it('acepta y normaliza celulares', function (string $celular, string $normalizado) {
    expect(CelularValidator::esValido($celular))->toBeTrue()
        ->and(CelularValidator::normalizar($celular))->toBe($normalizado);
})->with('celulares validos');

it('rechaza lo que no es un celular', function (string $celular) {
    expect(CelularValidator::esValido($celular))->toBeFalse()
        ->and(CelularValidator::normalizar($celular))->toBeNull();
})->with('celulares invalidos');
