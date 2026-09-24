<?php

use Aeunius\PeruRules\Enums\TipoContribuyente;
use Aeunius\PeruRules\Support\RucValidator;

it('obtiene el tipo desde el prefijo', function (string $prefijo, TipoContribuyente $tipo) {
    expect(TipoContribuyente::desdePrefijo($prefijo))->toBe($tipo);
})->with([
    ['10', TipoContribuyente::Natural],
    ['20', TipoContribuyente::Juridica],
    ['15', TipoContribuyente::Especial],
    ['16', TipoContribuyente::Especial],
    ['17', TipoContribuyente::Especial],
]);

it('no reconoce prefijos inexistentes', function (string $prefijo) {
    expect(TipoContribuyente::desdePrefijo($prefijo))->toBeNull();
})->with(['11', '30', '1', '']);

it('cubre todos los prefijos válidos del RUC', function () {
    foreach (RucValidator::PREFIJOS as $prefijo) {
        expect(TipoContribuyente::desdePrefijo($prefijo))->not->toBeNull();
    }
});

it('tiene descripción', function () {
    expect(TipoContribuyente::Natural->descripcion())->toBe('Persona natural')
        ->and(TipoContribuyente::Juridica->descripcion())->toBe('Persona jurídica')
        ->and(TipoContribuyente::Especial->descripcion())->toBe('Caso especial');
});
