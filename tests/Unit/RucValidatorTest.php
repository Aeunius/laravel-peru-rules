<?php

use Aeunius\PeruRules\Support\RucValidator;

it('acepta RUC válidos', function (string $ruc) {
    expect(RucValidator::isValid($ruc))->toBeTrue();
})->with('rucs validos');

it('rechaza RUC inválidos', function (string $ruc) {
    expect(RucValidator::isValid($ruc))->toBeFalse();
})->with('rucs invalidos');

it('rechaza el texto vacío', function () {
    expect(RucValidator::isValid(''))->toBeFalse();
});

it('calcula el dígito verificador', function (string $ruc) {
    expect(RucValidator::digitoVerificador(substr($ruc, 0, 10)))->toBe((int) $ruc[10]);
})->with('rucs validos');

it('acepta un solo dígito verificador por cada base', function (string $ruc) {
    $validos = array_filter(
        range(0, 9),
        fn (int $digito) => RucValidator::isValid(substr($ruc, 0, 10).$digito),
    );

    expect($validos)->toHaveCount(1);
})->with('rucs juridicos');

it('filtra por prefijo', function () {
    expect(RucValidator::isValid('20131312955', [RucValidator::JURIDICA]))->toBeTrue()
        ->and(RucValidator::isValid('20131312955', [RucValidator::NATURAL]))->toBeFalse()
        ->and(RucValidator::isValid('10123456781', [RucValidator::NATURAL]))->toBeTrue()
        ->and(RucValidator::isValid('10123456781', [RucValidator::JURIDICA]))->toBeFalse();
});

it('exige 10 dígitos para calcular el dígito verificador', function (string $base) {
    RucValidator::digitoVerificador($base);
})->with(['201313129', '20131312955', '201313129A'])->throws(InvalidArgumentException::class);
