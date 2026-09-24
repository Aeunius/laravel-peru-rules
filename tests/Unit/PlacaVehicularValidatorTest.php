<?php

use Aeunius\PeruRules\Support\PlacaVehicularValidator;

it('acepta y normaliza placas', function (string $placa, string $normalizada) {
    expect(PlacaVehicularValidator::esValido($placa))->toBeTrue()
        ->and(PlacaVehicularValidator::normalizar($placa))->toBe($normalizada);
})->with('placas validas');

it('rechaza lo que no es una placa', function (string $placa) {
    expect(PlacaVehicularValidator::esValido($placa))->toBeFalse()
        ->and(PlacaVehicularValidator::normalizar($placa))->toBeNull();
})->with('placas invalidas');
