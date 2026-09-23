<?php

namespace Aeunius\PeruRules;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PeruRulesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('peru-rules')
            ->hasConfigFile()
            ->hasTranslations();
    }
}
