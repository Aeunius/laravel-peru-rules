<?php

use Aeunius\PeruRules\Enums\TipoDocumento;
use Aeunius\PeruRules\Rules\CarneExtranjeria;
use Aeunius\PeruRules\Rules\Dni;
use Aeunius\PeruRules\Rules\DocumentoIdentidad;
use Aeunius\PeruRules\Rules\Pasaporte;
use Aeunius\PeruRules\Rules\Ruc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

function validaDocumento(array $datos, mixed $regla = null): Illuminate\Validation\Validator
{
    return Validator::make($datos, [
        'tipo_doc' => ['required', Rule::enum(TipoDocumento::class)],
        'num_doc' => ['required', $regla ?? DocumentoIdentidad::segun('tipo_doc')],
    ]);
}

it('valida el número según el tipo del otro campo', function (string $tipo, mixed $numero, bool $valido) {
    foreach ([DocumentoIdentidad::segun('tipo_doc'), 'documento_identidad:tipo_doc'] as $regla) {
        expect(validaDocumento(['tipo_doc' => $tipo, 'num_doc' => $numero], $regla)->passes())->toBe($valido);
    }
})->with([
    'DNI válido' => ['1', '12345678', true],
    'DNI como entero' => ['1', 12345678, true],
    'DNI con RUC' => ['1', '20131312955', false],
    'RUC válido' => ['6', '20131312955', true],
    'RUC con DNI' => ['6', '12345678', false],
    'carné de extranjería' => ['4', '001234567', true],
    'carné demasiado largo' => ['4', '0012345678901', false],
    'pasaporte' => ['7', 'AB1234567', true],
    'no domiciliado de 15' => ['0', '123456789012345', true],
    'no domiciliado de 16' => ['0', '1234567890123456', false],
    'cédula diplomática' => ['A', 'CD12345', true],
    'cédula con guion' => ['A', 'CD-12345', false],
]);

it('usa el mensaje de la regla de cada tipo', function (string $tipo, string $numero, string $mensaje) {
    app()->setLocale('es');

    expect(validaDocumento(['tipo_doc' => $tipo, 'num_doc' => $numero])->errors()->first('num_doc'))->toBe($mensaje);
})->with([
    'DNI' => ['1', '123', 'El campo num doc debe ser un DNI válido de 8 dígitos.'],
    'RUC' => ['6', '123', 'El campo num doc debe ser un RUC válido.'],
    'cédula' => ['A', 'CD-1', 'El campo num doc debe ser un número de cédula diplomática de identidad válido (hasta 15 letras o números).'],
    'no domiciliado' => ['0', '#', 'El campo num doc debe ser un número de documento tributario de no domiciliado sin RUC válido (hasta 15 letras o números).'],
]);

it('usa el mismo mensaje en la regla en texto', function () {
    app()->setLocale('es');

    $validator = validaDocumento(['tipo_doc' => 'A', 'num_doc' => 'CD-1'], 'documento_identidad:tipo_doc');

    expect($validator->errors()->first('num_doc'))
        ->toBe('El campo num doc debe ser un número de cédula diplomática de identidad válido (hasta 15 letras o números).');
});

it('rechaza el número si el tipo no es válido o falta', function (array $datos) {
    app()->setLocale('es');

    $validator = Validator::make($datos, ['num_doc' => [DocumentoIdentidad::segun('tipo_doc')]]);

    expect($validator->errors()->first('num_doc'))
        ->toBe('El campo num doc no se puede validar porque el tipo de documento no es válido.');
})->with([
    'tipo inexistente' => [['tipo_doc' => '9', 'num_doc' => '12345678']],
    'sin tipo' => [['num_doc' => '12345678']],
    'tipo como arreglo' => [['tipo_doc' => ['1'], 'num_doc' => '12345678']],
]);

it('acepta el enum como tipo', function () {
    $validator = Validator::make(
        ['tipo_doc' => TipoDocumento::Dni, 'num_doc' => '12345678'],
        ['num_doc' => [DocumentoIdentidad::segun('tipo_doc')]],
    );

    expect($validator->passes())->toBeTrue();
});

it('resuelve comodines en arreglos', function (mixed $regla) {
    $validator = Validator::make([
        'clientes' => [
            ['tipo_doc' => '1', 'num_doc' => '12345678'],
            ['tipo_doc' => '6', 'num_doc' => '20131312955'],
            ['tipo_doc' => '6', 'num_doc' => '12345678'],
        ],
    ], ['clientes.*.num_doc' => [$regla]]);

    expect($validator->errors()->keys())->toBe(['clientes.2.num_doc']);
})->with([
    'objeto' => fn () => DocumentoIdentidad::segun('clientes.*.tipo_doc'),
    'texto' => 'documento_identidad:clientes.*.tipo_doc',
]);

it('usa un tipo fijo', function () {
    expect(Validator::make(['n' => 'CD12345'], ['n' => [DocumentoIdentidad::de(TipoDocumento::CedulaDiplomatica)]])->passes())->toBeTrue()
        ->and(Validator::make(['n' => '1234567'], ['n' => [DocumentoIdentidad::de(TipoDocumento::Dni)]])->fails())->toBeTrue();
});

it('exige el campo del tipo en la regla en texto', function () {
    Validator::make(['n' => '12345678'], ['n' => 'documento_identidad'])->passes();
})->throws(InvalidArgumentException::class, 'documento_identidad:tipo_doc');

it('expone la regla de cada tipo', function (TipoDocumento $tipo, string $clase) {
    expect($tipo->regla())->toBeInstanceOf($clase);
})->with([
    [TipoDocumento::Dni, Dni::class],
    [TipoDocumento::Ruc, Ruc::class],
    [TipoDocumento::CarneExtranjeria, CarneExtranjeria::class],
    [TipoDocumento::Pasaporte, Pasaporte::class],
    [TipoDocumento::CedulaDiplomatica, DocumentoIdentidad::class],
    [TipoDocumento::NoDomiciliadoSinRuc, DocumentoIdentidad::class],
]);
