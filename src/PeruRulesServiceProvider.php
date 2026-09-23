<?php

namespace Aeunius\PeruRules;

use Aeunius\PeruRules\Rules\Dni;
use Aeunius\PeruRules\Rules\Ruc;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Validation\Validator;
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

    public function packageBooted(): void
    {
        // Reglas en texto: 'ruc', 'ruc:natural', 'ruc:juridica' y 'dni'.
        $this->extend('ruc', fn (array $parameters): ValidationRule => match ($parameters[0] ?? null) {
            'natural' => Ruc::natural(),
            'juridica' => Ruc::juridica(),
            null => new Ruc,
            default => throw new \InvalidArgumentException("La regla ruc no acepta el parámetro [{$parameters[0]}]; usa ruc, ruc:natural o ruc:juridica."),
        });

        $this->extend('dni', fn (): ValidationRule => new Dni);
    }

    /**
     * Registra una regla en texto que delega en la regla objeto que crea $factory.
     *
     * El mensaje se resuelve al validar y no al arrancar la aplicación, para que
     * respete el idioma activo en ese momento. Si la aplicación define
     * validation.<regla> en sus traducciones, ese mensaje tiene prioridad.
     *
     * @param  \Closure(array<int, string>): ValidationRule  $factory
     */
    private function extend(string $name, \Closure $factory): void
    {
        ValidatorFacade::extend($name, function (string $attribute, mixed $value, array $parameters, Validator $validator) use ($name, $factory): bool {
            /** @var array<int, string> $parameters */
            $failure = null;

            $factory($parameters)->validate($attribute, $value, function (string $message) use (&$failure): PotentiallyTranslatedString {
                return $failure = new PotentiallyTranslatedString($message, $this->app->make('translator'));
            });

            if ($failure === null) {
                return true;
            }

            $validator->fallbackMessages[$name] = (string) $failure;

            return false;
        });
    }
}
