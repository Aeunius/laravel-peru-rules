<?php

use Aeunius\PeruRules\Rules\Dni;
use Illuminate\Support\Facades\Validator;

function validaDni(mixed $valor, mixed $regla): Illuminate\Validation\Validator
{
    return Validator::make(['dni' => $valor], ['dni' => [$regla]]);
}

it('acepta un DNI válido', function (mixed $dni) {
    expect(validaDni($dni, new Dni)->passes())->toBeTrue()
        ->and(validaDni($dni, 'dni')->passes())->toBeTrue();
})->with(['texto' => '12345678', 'ceros a la izquierda' => '00123456', 'entero' => 12345678]);

it('rechaza un DNI inválido', function (mixed $dni) {
    expect(validaDni($dni, new Dni)->fails())->toBeTrue()
        ->and(validaDni($dni, 'dni')->fails())->toBeTrue();
})->with([
    '7 dígitos' => '1234567',
    '9 dígitos' => '123456789',
    'con letra' => '1234567A',
    'entero de 7 dígitos' => 123456,
    'arreglo' => [['12345678']],
]);

it('muestra el mensaje traducido', function (string $locale, string $mensaje) {
    app()->setLocale($locale);

    expect(validaDni('123', new Dni)->errors()->first('dni'))->toBe($mensaje)
        ->and(validaDni('123', 'dni')->errors()->first('dni'))->toBe($mensaje);
})->with([
    ['es', 'El campo dni debe ser un DNI válido de 8 dígitos.'],
    ['en', 'The dni field must be a valid 8-digit DNI.'],
]);
