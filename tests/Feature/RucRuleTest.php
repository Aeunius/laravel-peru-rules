<?php

use Aeunius\PeruRules\Rules\Ruc;
use Illuminate\Support\Facades\Validator;

function validaRuc(mixed $valor, mixed $regla): Illuminate\Validation\Validator
{
    return Validator::make(['ruc' => $valor], ['ruc' => [$regla]]);
}

it('acepta un RUC válido', function (string $ruc) {
    expect(validaRuc($ruc, new Ruc)->passes())->toBeTrue()
        ->and(validaRuc($ruc, 'ruc')->passes())->toBeTrue();
})->with('rucs validos');

it('rechaza un RUC inválido', function (string $ruc) {
    expect(validaRuc($ruc, new Ruc)->fails())->toBeTrue()
        ->and(validaRuc($ruc, 'ruc')->fails())->toBeTrue();
})->with('rucs invalidos');

it('acepta el RUC como entero', function () {
    expect(validaRuc(20131312955, new Ruc)->passes())->toBeTrue()
        ->and(validaRuc(20131312955, 'ruc')->passes())->toBeTrue();
});

it('rechaza valores que no son texto ni entero', function (mixed $valor) {
    expect(validaRuc($valor, new Ruc)->fails())->toBeTrue()
        ->and(validaRuc($valor, 'ruc')->fails())->toBeTrue();
})->with([
    'arreglo' => [['20131312955']],
    'decimal' => 20131312955.0,
    'booleano' => true,
]);

it('limita a persona natural', function () {
    expect(validaRuc('10123456781', Ruc::natural())->passes())->toBeTrue()
        ->and(validaRuc('20131312955', Ruc::natural())->fails())->toBeTrue()
        ->and(validaRuc('10123456781', 'ruc:natural')->passes())->toBeTrue()
        ->and(validaRuc('20131312955', 'ruc:natural')->fails())->toBeTrue();
});

it('limita a persona jurídica', function () {
    expect(validaRuc('20131312955', Ruc::juridica())->passes())->toBeTrue()
        ->and(validaRuc('10123456781', Ruc::juridica())->fails())->toBeTrue()
        ->and(validaRuc('20131312955', 'ruc:juridica')->passes())->toBeTrue()
        ->and(validaRuc('10123456781', 'ruc:juridica')->fails())->toBeTrue();
});

it('no valida un campo vacío opcional', function () {
    expect(Validator::make(['ruc' => null], ['ruc' => ['nullable', new Ruc]])->passes())->toBeTrue()
        ->and(Validator::make(['ruc' => null], ['ruc' => 'nullable|ruc'])->passes())->toBeTrue();
});

it('rechaza un parámetro desconocido en la regla en texto', function () {
    validaRuc('20131312955', 'ruc:mixta')->passes();
})->throws(InvalidArgumentException::class, 'ruc:natural');

it('muestra el mensaje en español', function (mixed $regla, string $mensaje) {
    app()->setLocale('es');

    expect(validaRuc('123', $regla)->errors()->first('ruc'))->toBe($mensaje);
})->with([
    'objeto' => [new Ruc, 'El campo ruc debe ser un RUC válido.'],
    'texto' => ['ruc', 'El campo ruc debe ser un RUC válido.'],
    'natural' => [Ruc::natural(), 'El campo ruc debe ser un RUC de persona natural válido (empieza con 10).'],
    'ruc:natural' => ['ruc:natural', 'El campo ruc debe ser un RUC de persona natural válido (empieza con 10).'],
    'juridica' => [Ruc::juridica(), 'El campo ruc debe ser un RUC de persona jurídica válido (empieza con 20).'],
    'ruc:juridica' => ['ruc:juridica', 'El campo ruc debe ser un RUC de persona jurídica válido (empieza con 20).'],
]);

it('muestra el mensaje en inglés', function (mixed $regla) {
    app()->setLocale('en');

    expect(validaRuc('123', $regla)->errors()->first('ruc'))->toBe('The ruc field must be a valid RUC.');
})->with(['objeto' => new Ruc, 'texto' => 'ruc']);

it('respeta el idioma activo al validar, no el del arranque', function () {
    app()->setLocale('en');
    validaRuc('123', 'ruc')->errors();

    app()->setLocale('es');

    expect(validaRuc('123', 'ruc')->errors()->first('ruc'))->toBe('El campo ruc debe ser un RUC válido.');
});

it('usa el nombre del atributo y los mensajes de la aplicación', function () {
    app()->setLocale('es');

    $validator = Validator::make(
        ['ruc_empresa' => '123'],
        ['ruc_empresa' => 'ruc'],
        ['ruc_empresa.ruc' => 'Revisa el RUC.'],
    );

    expect($validator->errors()->first('ruc_empresa'))->toBe('Revisa el RUC.');

    app('translator')->addLines(['validation.ruc' => 'Mensaje de la app para :attribute.'], 'es');

    expect(validaRuc('123', 'ruc')->errors()->first('ruc'))->toBe('Mensaje de la app para ruc.');
});
