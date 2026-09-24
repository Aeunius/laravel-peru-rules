<?php

use Aeunius\PeruRules\Enums\TipoContribuyente;
use Aeunius\PeruRules\ValueObjects\Ruc;

it('se crea con cualquier RUC válido', function (string $ruc) {
    expect(Ruc::from($ruc)->valor())->toBe($ruc)
        ->and(Ruc::tryFrom($ruc))->not->toBeNull();
})->with('rucs validos');

it('no se crea con un RUC inválido', function (string $ruc) {
    expect(Ruc::tryFrom($ruc))->toBeNull()
        ->and(fn () => Ruc::from($ruc))->toThrow(InvalidArgumentException::class, 'no es un RUC válido');
})->with('rucs invalidos');

it('acepta el RUC como entero', function () {
    expect(Ruc::from(20131312955)->valor())->toBe('20131312955');
});

it('da el formato con guiones', function () {
    expect(Ruc::from('20131312955')->formateado())->toBe('20-13131295-5')
        ->and(Ruc::from('10123456781')->formateado())->toBe('10-12345678-1');
});

it('da el tipo de contribuyente', function (string $ruc, TipoContribuyente $tipo, bool $natural, bool $juridica) {
    $objeto = Ruc::from($ruc);

    expect($objeto->tipo())->toBe($tipo)
        ->and($objeto->prefijo())->toBe(substr($ruc, 0, 2))
        ->and($objeto->esNatural())->toBe($natural)
        ->and($objeto->esJuridica())->toBe($juridica);
})->with([
    'natural' => ['10123456781', TipoContribuyente::Natural, true, false],
    'jurídica' => ['20131312955', TipoContribuyente::Juridica, false, true],
    'especial' => ['15123456782', TipoContribuyente::Especial, false, false],
]);

it('extrae el DNI solo de una persona natural', function () {
    expect(Ruc::from('10123456781')->dni())->toBe('12345678')
        ->and(Ruc::from('20131312955')->dni())->toBeNull()
        ->and(Ruc::from('17123456785')->dni())->toBeNull();
});

it('compara por valor', function () {
    expect(Ruc::from('20131312955')->esIgual(Ruc::from(20131312955)))->toBeTrue()
        ->and(Ruc::from('20131312955')->esIgual(Ruc::from('20100047218')))->toBeFalse();
});

it('se convierte a texto y a JSON como los 11 dígitos', function () {
    $ruc = Ruc::from('20131312955');

    expect((string) $ruc)->toBe('20131312955')
        ->and(json_encode(['ruc' => $ruc]))->toBe('{"ruc":"20131312955"}');
});
