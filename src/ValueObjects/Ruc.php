<?php

namespace Aeunius\PeruRules\ValueObjects;

use Aeunius\PeruRules\Casts\RucCast;
use Aeunius\PeruRules\Enums\TipoContribuyente;
use Aeunius\PeruRules\Support\RucValidator;
use Illuminate\Contracts\Database\Eloquent\Castable;
use InvalidArgumentException;
use JsonSerializable;
use Stringable;

/**
 * Un RUC válido. Solo se puede crear con un número que pasa la validación, así
 * que un objeto Ruc nunca contiene un RUC inválido.
 *
 * Como cast de Eloquent: 'ruc' => Ruc::class.
 */
final readonly class Ruc implements Castable, JsonSerializable, Stringable
{
    private function __construct(private string $numero) {}

    /**
     * @throws InvalidArgumentException si no es un RUC válido.
     */
    public static function from(string|int $ruc): self
    {
        return self::tryFrom($ruc)
            ?? throw new InvalidArgumentException("[{$ruc}] no es un RUC válido.");
    }

    public static function tryFrom(string|int $ruc): ?self
    {
        $ruc = (string) $ruc;

        return RucValidator::isValid($ruc) ? new self($ruc) : null;
    }

    /** Los 11 dígitos. */
    public function valor(): string
    {
        return $this->numero;
    }

    /** "20-13131295-5": prefijo, cuerpo y dígito verificador. */
    public function formateado(): string
    {
        return substr($this->numero, 0, 2).'-'.substr($this->numero, 2, 8).'-'.$this->numero[10];
    }

    public function prefijo(): string
    {
        return substr($this->numero, 0, 2);
    }

    public function tipo(): TipoContribuyente
    {
        // El prefijo ya se validó en tryFrom(): siempre corresponde a un tipo.
        return TipoContribuyente::desdePrefijo($this->prefijo()) ?? TipoContribuyente::Especial;
    }

    public function esNatural(): bool
    {
        return $this->tipo() === TipoContribuyente::Natural;
    }

    public function esJuridica(): bool
    {
        return $this->tipo() === TipoContribuyente::Juridica;
    }

    /** El DNI de una persona natural, o null en los demás tipos. */
    public function dni(): ?string
    {
        return $this->esNatural() ? substr($this->numero, 2, 8) : null;
    }

    public function esIgual(self $otro): bool
    {
        return $this->numero === $otro->numero;
    }

    public function __toString(): string
    {
        return $this->numero;
    }

    public function jsonSerialize(): string
    {
        return $this->numero;
    }

    /**
     * @param  array<int, mixed>  $arguments
     * @return class-string<RucCast>
     */
    public static function castUsing(array $arguments): string
    {
        return RucCast::class;
    }
}
