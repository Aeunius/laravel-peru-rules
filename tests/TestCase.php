<?php

namespace Aeunius\PeruRules\Tests;

use Aeunius\PeruRules\PeruRulesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PeruRulesServiceProvider::class,
        ];
    }
}
