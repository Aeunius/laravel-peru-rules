<?php

namespace Aeunius\PeruRules;

use Aeunius\PeruRules\Rules\CarneExtranjeria;
use Aeunius\PeruRules\Rules\Cci;
use Aeunius\PeruRules\Rules\Celular;
use Aeunius\PeruRules\Rules\Dni;
use Aeunius\PeruRules\Rules\DocumentoIdentidad;
use Aeunius\PeruRules\Rules\Pasaporte;
use Aeunius\PeruRules\Rules\PlacaVehicular;
use Aeunius\PeruRules\Rules\Ruc;
use Illuminate\Contracts\Validation\DataAwareRule;
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
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        // Reglas en texto: el nombre de cada regla en snake_case.
        $this->extend('ruc', fn (array $parameters): ValidationRule => match ($parameters[0] ?? null) {
            'natural' => Ruc::natural(),
            'juridica' => Ruc::juridica(),
            null => new Ruc,
            default => throw new \InvalidArgumentException("La regla ruc no acepta el parámetro [{$parameters[0]}]; usa ruc, ruc:natural o ruc:juridica."),
        });

        $this->extend('dni', fn (): ValidationRule => new Dni);
        $this->extend('carne_extranjeria', fn (): ValidationRule => new CarneExtranjeria);
        $this->extend('pasaporte', fn (): ValidationRule => new Pasaporte);
        $this->extend('celular', fn (): ValidationRule => new Celular);
        $this->extend('placa_vehicular', fn (): ValidationRule => new PlacaVehicular);
        $this->extend('cci', fn (): ValidationRule => new Cci);

        $this->extend('documento_identidad', fn (array $parameters): ValidationRule => isset($parameters[0])
            ? DocumentoIdentidad::segun($parameters[0])
            : throw new \InvalidArgumentException('La regla documento_identidad necesita el campo del tipo: documento_identidad:tipo_doc.'));
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

            $rule = $factory($parameters);

            if ($rule instanceof DataAwareRule) {
                $rule->setData($validator->getData());
            }

            $rule->validate($attribute, $value, function (string $message) use (&$failure): PotentiallyTranslatedString {
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
