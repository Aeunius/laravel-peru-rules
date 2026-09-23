<?php

use Aeunius\PeruRules\Rules\CarneExtranjeria;
use Aeunius\PeruRules\Rules\Cci;
use Aeunius\PeruRules\Rules\Celular;
use Aeunius\PeruRules\Rules\Pasaporte;
use Aeunius\PeruRules\Rules\PlacaVehicular;
use Illuminate\Support\Facades\Validator;

function valida(mixed $valor, mixed $regla): Illuminate\Validation\Validator
{
    return Validator::make(['campo' => $valor], ['campo' => [$regla]]);
}

it('valida el carné de extranjería', function (mixed $regla) {
    expect(valida('001234567', $regla)->passes())->toBeTrue()
        ->and(valida(1234567, $regla)->passes())->toBeTrue()
        ->and(valida('0012345678901', $regla)->fails())->toBeTrue()
        ->and(valida('001-234567', $regla)->fails())->toBeTrue();
})->with(['objeto' => new CarneExtranjeria, 'texto' => 'carne_extranjeria']);

it('valida el pasaporte', function (mixed $regla) {
    expect(valida('AB1234567', $regla)->passes())->toBeTrue()
        ->and(valida('AB12345678901', $regla)->fails())->toBeTrue()
        ->and(valida('AB 1234567', $regla)->fails())->toBeTrue();
})->with(['objeto' => new Pasaporte, 'texto' => 'pasaporte']);

it('acepta celulares', function (string $celular) {
    expect(valida($celular, new Celular)->passes())->toBeTrue()
        ->and(valida($celular, 'celular')->passes())->toBeTrue();
})->with('celulares validos');

it('rechaza lo que no es un celular', function (mixed $celular) {
    expect(valida($celular, new Celular)->fails())->toBeTrue()
        ->and(valida($celular, 'celular')->fails())->toBeTrue();
})->with('celulares invalidos');

it('acepta el celular como entero', function () {
    expect(valida(987654321, new Celular)->passes())->toBeTrue();
});

it('acepta placas', function (string $placa) {
    expect(valida($placa, new PlacaVehicular)->passes())->toBeTrue()
        ->and(valida($placa, 'placa_vehicular')->passes())->toBeTrue();
})->with('placas validas');

it('rechaza lo que no es una placa', function (string $placa) {
    expect(valida($placa, new PlacaVehicular)->fails())->toBeTrue()
        ->and(valida($placa, 'placa_vehicular')->fails())->toBeTrue();
})->with('placas invalidas');

it('acepta CCI', function (string $cci) {
    expect(valida($cci, new Cci)->passes())->toBeTrue()
        ->and(valida($cci, 'cci')->passes())->toBeTrue();
})->with('ccis validos');

it('rechaza lo que no es un CCI', function (mixed $cci) {
    expect(valida($cci, new Cci)->fails())->toBeTrue()
        ->and(valida($cci, 'cci')->fails())->toBeTrue();
})->with('ccis invalidos');

it('rechaza el CCI como entero', function () {
    // 20 dígitos no caben en un entero de PHP: el CCI siempre llega como texto.
    expect(valida(219100012345678957, new Cci)->fails())->toBeTrue();
});

it('muestra el mensaje traducido', function (mixed $regla, string $es, string $en) {
    app()->setLocale('es');
    expect(valida('#', $regla)->errors()->first('campo'))->toBe($es);

    app()->setLocale('en');
    expect(valida('#', $regla)->errors()->first('campo'))->toBe($en);
})->with([
    'carné' => [
        'carne_extranjeria',
        'El campo campo debe ser un carné de extranjería válido (hasta 12 letras o números).',
        'The campo field must be a valid foreigner ID card number (up to 12 letters or digits).',
    ],
    'pasaporte' => [
        new Pasaporte,
        'El campo campo debe ser un pasaporte válido (hasta 12 letras o números).',
        'The campo field must be a valid passport number (up to 12 letters or digits).',
    ],
    'celular' => [
        'celular',
        'El campo campo debe ser un celular válido de 9 dígitos que empiece con 9.',
        'The campo field must be a valid 9-digit mobile number starting with 9.',
    ],
    'placa' => [
        new PlacaVehicular,
        'El campo campo debe ser una placa vehicular válida (por ejemplo, ABC-123 o 2171-AY).',
        'The campo field must be a valid license plate (for example, ABC-123 or 2171-AY).',
    ],
    'cci' => [
        'cci',
        'El campo campo debe ser un CCI válido de 20 dígitos.',
        'The campo field must be a valid 20-digit CCI.',
    ],
]);
