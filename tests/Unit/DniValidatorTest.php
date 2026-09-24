<?php

use Aeunius\PeruRules\Support\DniValidator;

it('acepta DNI de 8 dígitos', function (string $dni) {
    expect(DniValidator::esValido($dni))->toBeTrue();
})->with(['12345678', '00123456', '99999999']);

it('rechaza lo que no son 8 dígitos', function (string $dni) {
    expect(DniValidator::esValido($dni))->toBeFalse();
})->with([
    '7 dígitos' => '1234567',
    '9 dígitos' => '123456789',
    'con letra' => '1234567A',
    'con dígito verificador' => '12345678-9',
    'con espacios' => ' 12345678',
    'vacío' => '',
]);
