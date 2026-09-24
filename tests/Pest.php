<?php

use Aeunius\PeruRules\Tests\TestCase;

// Feature: dentro de una aplicación Laravel simulada con Testbench.
// Unit: algoritmos puros de src/Support, sin Laravel.
uses(TestCase::class)->in('Feature');

/**
 * Casos de tests/fixtures/{$nombre}.json. Los comparte el paquete de JavaScript
 * aeunius/peru-rules-js, que los descarga de una etiqueta de este repositorio.
 *
 * @return array<string, mixed>
 */
function casos(string $nombre): array
{
    return json_decode(file_get_contents(__DIR__."/fixtures/{$nombre}.json"), true, flags: JSON_THROW_ON_ERROR);
}
