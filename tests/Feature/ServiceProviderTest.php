<?php

use Aeunius\PeruRules\PeruRulesServiceProvider;
use Illuminate\Support\ServiceProvider;

it('registra el service provider', function () {
    expect(app()->getProviders(PeruRulesServiceProvider::class))->not->toBeEmpty();
});

it('carga la configuración del paquete', function () {
    expect(config('peru-rules'))->toBeArray();
});

it('registra el namespace de traducciones', function () {
    expect(app('translator')->getLoader()->namespaces())->toHaveKey('peru-rules');
});

it('publica la configuración y las traducciones', function () {
    expect(ServiceProvider::publishableGroups())
        ->toContain('peru-rules-config')
        ->toContain('peru-rules-translations');
});
