<?php

use Aeunius\PeruRules\Support\DniValidator;

it('acepta DNI de 8 dígitos', function (string $dni) {
    expect(DniValidator::esValido($dni))->toBeTrue();
})->with('dnis validos');

it('rechaza lo que no son 8 dígitos', function (string $dni) {
    expect(DniValidator::esValido($dni))->toBeFalse();
})->with('dnis invalidos');
