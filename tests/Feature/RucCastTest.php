<?php

use Aeunius\PeruRules\Casts\RucCast;
use Aeunius\PeruRules\ValueObjects\Ruc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'ruc' => RucCast::class,
            'ruc_proveedor' => Ruc::class,
        ];
    }
}

beforeEach(function () {
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();
        $table->string('ruc', 11)->nullable();
        $table->string('ruc_proveedor', 11)->nullable();
    });
});

it('guarda el texto y devuelve el objeto', function (string $atributo) {
    $cliente = Cliente::create([$atributo => '20131312955']);

    expect(DB::table('clientes')->value($atributo))->toBe('20131312955')
        ->and($cliente->fresh()->{$atributo})->toBeInstanceOf(Ruc::class)
        ->and($cliente->fresh()->{$atributo}->formateado())->toBe('20-13131295-5');
})->with(['con RucCast' => 'ruc', 'con Ruc::class' => 'ruc_proveedor']);

it('acepta un objeto Ruc o un entero', function () {
    $cliente = Cliente::create(['ruc' => Ruc::from('20131312955'), 'ruc_proveedor' => 20100047218]);

    expect(DB::table('clientes')->first())
        ->ruc->toBe('20131312955')
        ->ruc_proveedor->toBe('20100047218');
});

it('respeta los nulos', function () {
    $cliente = Cliente::create(['ruc' => null]);

    expect($cliente->fresh()->ruc)->toBeNull();
});

it('no guarda un RUC inválido', function (mixed $valor) {
    Cliente::create(['ruc' => $valor]);
})->with([
    'dígito errado' => '20131312956',
    'formateado' => '20-13131295-5',
    'arreglo' => [['20131312955']],
])->throws(InvalidArgumentException::class);

it('avisa si la base de datos tiene un RUC inválido', function () {
    DB::table('clientes')->insert(['ruc' => '20131312956']);

    Cliente::first()->ruc;
})->throws(InvalidArgumentException::class, '[20131312956] no es un RUC válido.');

it('serializa el modelo con los 11 dígitos', function () {
    $cliente = Cliente::create(['ruc' => '20131312955']);

    expect($cliente->fresh()->toArray()['ruc'])->toBe('20131312955')
        ->and($cliente->fresh()->toJson())->toContain('"ruc":"20131312955"');
});
