<?php

namespace Aeunius\PeruRules\Casts;

use Aeunius\PeruRules\ValueObjects\Ruc;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Guarda el RUC como texto de 11 dígitos y lo devuelve como ValueObjects\Ruc.
 *
 * Es estricto en los dos sentidos: no se puede guardar un RUC inválido, y leer
 * uno inválido de la base de datos lanza una excepción en vez de ocultarlo.
 *
 * En toArray() y toJson() el RUC sale como sus 11 dígitos.
 *
 * @implements CastsAttributes<Ruc|null, mixed>
 */
final class RucCast implements CastsAttributes, SerializesCastableAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Ruc
    {
        if ($value === null) {
            return null;
        }

        if (! is_string($value) && ! is_int($value)) {
            throw new InvalidArgumentException("El atributo [{$key}] no contiene un RUC.");
        }

        return Ruc::from($value);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Ruc) {
            return $value->valor();
        }

        if (! is_string($value) && ! is_int($value)) {
            throw new InvalidArgumentException("El atributo [{$key}] solo acepta un RUC como texto, entero u objeto Ruc.");
        }

        return Ruc::from($value)->valor();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value instanceof Ruc ? $value->valor() : null;
    }
}
