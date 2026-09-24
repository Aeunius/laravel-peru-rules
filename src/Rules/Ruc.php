<?php

namespace Aeunius\PeruRules\Rules;

use Aeunius\PeruRules\Support\RucValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * RUC de 11 dígitos con prefijo y dígito verificador válidos.
 */
final class Ruc implements ValidationRule
{
    /** @var list<string> */
    private array $prefijos = RucValidator::PREFIJOS;

    private string $mensaje = 'ruc';

    /** Solo RUC de persona natural (prefijo 10). */
    public static function natural(): self
    {
        return self::soloPrefijo(RucValidator::NATURAL, 'ruc_natural');
    }

    /** Solo RUC de persona jurídica (prefijo 20). */
    public static function juridica(): self
    {
        return self::soloPrefijo(RucValidator::JURIDICA, 'ruc_juridica');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_int($value)) {
            $value = (string) $value;
        }

        if (! is_string($value) || ! RucValidator::esValido($value, $this->prefijos)) {
            $fail("peru-rules::validation.{$this->mensaje}")->translate();
        }
    }

    private static function soloPrefijo(string $prefijo, string $mensaje): self
    {
        $rule = new self;
        $rule->prefijos = [$prefijo];
        $rule->mensaje = $mensaje;

        return $rule;
    }
}
