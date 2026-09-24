<?php

use Aeunius\PeruRules\PeruRulesServiceProvider;
use Illuminate\Support\ServiceProvider;

it('registra el service provider', function () {
    expect(app()->getProviders(PeruRulesServiceProvider::class))->not->toBeEmpty();
});

it('registra el namespace de traducciones', function () {
    expect(app('translator')->getLoader()->namespaces())->toHaveKey('peru-rules');
});

it('publica las traducciones y ninguna configuración', function () {
    expect(ServiceProvider::publishableGroups())
        ->toContain('peru-rules-translations')
        ->not->toContain('peru-rules-config');
});
