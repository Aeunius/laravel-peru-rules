<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Enums\TipoDocumento;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Número de documento validado según su tipo del catálogo 06 de la SUNAT.
 */
final class DocumentoIdentidad implements DataAwareRule, ValidationRule
{
    /** @var array<array-key, mixed> */
    private array $data = [];

    private function __construct(
        private readonly ?TipoDocumento $tipo,
        private readonly ?string $campoTipo,
    ) {}

    /**
     * Toma el tipo de otro campo del request, con los códigos del catálogo 06.
     *
     * Acepta comodines: con segun('clientes.*.tipo_doc'), el número de
     * clientes.2.num_doc se valida con el tipo de clientes.2.tipo_doc.
     */
    public static function segun(string $campoTipo): self
    {
        return new self(null, $campoTipo);
    }

    /** Usa siempre el mismo tipo de documento. */
    public static function de(TipoDocumento $tipo): self
    {
        return new self($tipo, null);
    }

    /**
     * @param  array<array-key, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tipo = $this->tipo ?? $this->tipoDelRequest($attribute);

        if ($tipo === null) {
            $fail('peru-rules::validation.documento_tipo')->translate();

            return;
        }

        // DNI, RUC, carné de extranjería y pasaporte tienen su propia regla y su
        // propio mensaje; los demás tipos se validan aquí.
        $regla = $tipo->regla();

        if (! $regla instanceof self) {
            $regla->validate($attribute, $value, $fail);

            return;
        }

        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || ! $tipo->esValido($value)) {
            $fail('peru-rules::validation.documento')->translate([
                'tipo' => Str::lcfirst($tipo->descripcion()),
                'max' => $tipo->longitudMaxima(),
            ]);
        }
    }

    private function tipoDelRequest(string $attribute): ?TipoDocumento
    {
        $tipo = Arr::get($this->data, $this->conIndices((string) $this->campoTipo, $attribute));

        if ($tipo instanceof TipoDocumento) {
            return $tipo;
        }

        return is_string($tipo) || is_int($tipo) ? TipoDocumento::tryFrom((string) $tipo) : null;
    }

    /**
     * Reemplaza cada * del campo por el segmento que ocupa esa posición en el
     * atributo validado: "clientes.*.tipo_doc" y "clientes.2.num_doc" dan
     * "clientes.2.tipo_doc".
     */
    private function conIndices(string $campo, string $attribute): string
    {
        $segmentos = explode('.', $attribute);

        return implode('.', array_map(
            fn (string $segmento, int $i): string => $segmento === '*' ? ($segmentos[$i] ?? '*') : $segmento,
            $partes = explode('.', $campo),
            array_keys($partes),
        ));
    }
}
