# Laravel Peru Rules

Reglas de validación de Laravel para documentos y datos peruanos: RUC, DNI,
carné de extranjería, pasaporte, celular, placa vehicular y CCI. Validan el
formato y los dígitos de control sin conectarse a ningún servicio externo.

[![tests](https://github.com/Aeunius/laravel-peru-rules/actions/workflows/tests.yml/badge.svg)](https://github.com/Aeunius/laravel-peru-rules/actions/workflows/tests.yml)
[![Versión en Packagist](https://img.shields.io/packagist/v/aeunius/laravel-peru-rules.svg)](https://packagist.org/packages/aeunius/laravel-peru-rules)
[![Descargas](https://img.shields.io/packagist/dt/aeunius/laravel-peru-rules.svg)](https://packagist.org/packages/aeunius/laravel-peru-rules)
[![Licencia](https://img.shields.io/packagist/l/aeunius/laravel-peru-rules.svg)](LICENSE.md)

## Qué valida

| Regla | En texto | Qué comprueba |
|---|---|---|
| `new Ruc` | `ruc` | 11 dígitos, prefijo `10`, `15`, `16`, `17` o `20`, y dígito verificador |
| `Ruc::natural()` | `ruc:natural` | Lo mismo, pero solo con el prefijo `10` (persona natural) |
| `Ruc::juridica()` | `ruc:juridica` | Lo mismo, pero solo con el prefijo `20` (persona jurídica) |
| `new Dni` | `dni` | 8 dígitos |
| `new CarneExtranjeria` | `carne_extranjeria` | Hasta 12 letras o números |
| `new Pasaporte` | `pasaporte` | Hasta 12 letras o números |
| `DocumentoIdentidad::segun('tipo_doc')` | `documento_identidad:tipo_doc` | El número según el tipo de documento de otro campo (catálogo 06 de la SUNAT) |
| `new Celular` | `celular` | 9 dígitos que empiezan con 9; acepta `+51` y separadores |
| `new PlacaVehicular` | `placa_vehicular` | Autos: `ABC-123` o `A1B-234`, con o sin guion. Especiales con prefijo E: `E GA-123`, `eGA-123`. Motos: `2171-AY`, `5040-6C`, `C5-4481` |
| `new Cci` | `cci` | 20 dígitos y los dos dígitos de control; acepta espacios y guiones |

Se comprueba que el número sea **válido**, no que **exista**: un RUC puede tener
un dígito verificador correcto y aun así no estar inscrito o no estar activo en
la SUNAT.

## Requisitos

- PHP 8.2 o superior
- Laravel 12 o 13

## Instalación

```bash
composer require aeunius/laravel-peru-rules
```

El service provider se registra solo.

## Uso

```php
use Aeunius\PeruRules\Rules\{Cci, Celular, Dni, PlacaVehicular, Ruc};

$request->validate([
    'ruc'         => ['required', new Ruc],
    'ruc_empresa' => ['required', Ruc::juridica()],
    'dni'         => ['required', new Dni],
    'celular'     => ['required', new Celular],
    'placa'       => ['nullable', new PlacaVehicular],
    'cci'         => ['required', new Cci],
]);
```

También como reglas en texto:

```php
$request->validate([
    'ruc'         => 'required|ruc',
    'ruc_empresa' => 'required|ruc:juridica',
    'dni'         => 'nullable|dni',
]);
```

Los valores se validan tal como llegan: `20-13131295-5` o un RUC con espacios no
pasan. Si tu formulario los admite, límpialos antes de validar. Se aceptan
textos y enteros; un DNI con ceros a la izquierda (`00123456`) solo llega
completo como texto.

Como cualquier regla de Laravel que no es `required`, las reglas no se aplican a
un campo vacío.

### Documento según su tipo

Cuando el formulario pide el tipo y el número de documento, `DocumentoIdentidad`
valida el número con la regla que corresponde al tipo. Los tipos son los del
**catálogo 06 de la SUNAT**, los mismos de la facturación electrónica:

```php
use Aeunius\PeruRules\Enums\TipoDocumento;
use Aeunius\PeruRules\Rules\DocumentoIdentidad;
use Illuminate\Validation\Rule;

$request->validate([
    'tipo_doc' => ['required', Rule::enum(TipoDocumento::class)],
    'num_doc'  => ['required', DocumentoIdentidad::segun('tipo_doc')],
]);
```

| Código | Tipo | Se valida con |
|---|---|---|
| `0` | Documento tributario de no domiciliado sin RUC | Hasta 15 letras o números |
| `1` | DNI | `Dni` |
| `4` | Carné de extranjería | `CarneExtranjeria` |
| `6` | RUC | `Ruc` |
| `7` | Pasaporte | `Pasaporte` |
| `A` | Cédula diplomática de identidad | Hasta 15 letras o números |

El mensaje de error es el de la regla de cada tipo. Si el tipo falta o no es
válido, el número tampoco pasa. En arreglos se usan comodines:
`DocumentoIdentidad::segun('clientes.*.tipo_doc')` valida `clientes.2.num_doc`
con `clientes.2.tipo_doc`.

El enum también sirve fuera de la validación:

```php
TipoDocumento::from('6')->descripcion();        // "RUC"
TipoDocumento::Pasaporte->longitudMaxima();     // 12
TipoDocumento::Dni->isValid('12345678');        // true
```

### RUC como objeto en tus modelos

`ValueObjects\Ruc` representa un RUC que ya pasó la validación: no se puede crear
con uno inválido. Úsalo como cast de Eloquent:

```php
use Aeunius\PeruRules\ValueObjects\Ruc;

class Cliente extends Model
{
    protected function casts(): array
    {
        return [
            'ruc' => Ruc::class,   // o RucCast::class
        ];
    }
}
```

```php
$cliente->ruc->valor();        // "20131312955"
$cliente->ruc->formateado();   // "20-13131295-5"
$cliente->ruc->tipo();         // TipoContribuyente::Juridica
$cliente->ruc->esJuridica();   // true
$cliente->ruc->dni();          // null; en un RUC 10 da los 8 dígitos del DNI

$cliente->ruc = '20100047218';             // o un entero, o un objeto Ruc
$cliente->ruc = '20100047219';             // InvalidArgumentException
```

En la base de datos se guardan los 11 dígitos como texto, y `toArray()` y
`toJson()` también devuelven los 11 dígitos. El cast es estricto al leer: si la
tabla tiene un RUC inválido, lanza una excepción en vez de devolver `null`.

Fuera de Eloquent:

```php
Ruc::from('20131312955');      // Ruc, o InvalidArgumentException
Ruc::tryFrom('20131312956');   // null
```

Si en el mismo archivo usas también la regla `Rules\Ruc`, importa uno de los dos
con alias: `use Aeunius\PeruRules\ValueObjects\Ruc as RucValor;`.

### Celular, placa y CCI: normalizar antes de guardar

Las reglas `Celular`, `PlacaVehicular` y `Cci` aceptan varias formas de escribir el mismo
dato. Para guardarlo siempre igual, normalízalo, por ejemplo en
`prepareForValidation()` de un Form Request:

```php
use Aeunius\PeruRules\Support\CciValidator;
use Aeunius\PeruRules\Support\CelularValidator;
use Aeunius\PeruRules\Support\PlacaVehicularValidator;

CelularValidator::normalizar('+51 987 654 321');   // "987654321"
PlacaVehicularValidator::normalizar('abc123');     // "ABC-123"
PlacaVehicularValidator::normalizar('e GA-123');   // "EGA-123"
PlacaVehicularValidator::normalizar('2171ay');     // "2171-AY"
CciValidator::normalizar('002-191-000123456789-57'); // "00219100012345678957"
CelularValidator::normalizar('014567890');         // null: no es un celular
```

En las placas de motos del tipo `C5-4481` el guion es obligatorio: sin él,
`C54481` se lee como la placa de auto `C54-481`.

### Sin Laravel

Los algoritmos no dependen de Laravel y se pueden usar directamente:

```php
use Aeunius\PeruRules\Support\RucValidator;

RucValidator::isValid('20131312955');                                  // true
RucValidator::isValid('20131312955', [RucValidator::NATURAL]);         // false
RucValidator::digitoVerificador('2013131295');                         // 5
```

## Mensajes

Los mensajes vienen en español e inglés y siguen el idioma activo de la
aplicación. Para cambiarlos, publica las traducciones:

```bash
php artisan vendor:publish --tag=peru-rules-translations
```

y edita `lang/vendor/peru-rules/{es,en}/validation.php`. En las reglas en texto
también tienen prioridad los mensajes de tu aplicación: `validation.ruc` en
`lang/es/validation.php` o el arreglo de mensajes del validador.

## Cómo se valida el RUC

El RUC tiene 11 dígitos: un prefijo de 2, 8 dígitos de identificación y un
dígito verificador. Para una persona natural, los 8 del medio son su DNI.

1. Multiplica los 10 primeros dígitos por los pesos `5 4 3 2 7 6 5 4 3 2`.
2. Suma los productos.
3. Calcula `r = 11 − (suma mod 11)`. Si `r` es 10, el dígito es `0`; si es 11, es `1`.
4. `r` tiene que ser igual al último dígito.

Por ejemplo, para el RUC de la SUNAT, `20131312955`:

```
2×5 + 0×4 + 1×3 + 3×2 + 1×7 + 3×6 + 1×5 + 2×4 + 9×3 + 5×2 = 94
94 mod 11 = 6   →   11 − 6 = 5   →   dígito verificador 5 ✓
```

## Cómo se valida el CCI

El CCI tiene 20 dígitos: entidad (3), oficina (3), cuenta (12) y dos dígitos de
control. El primero verifica entidad + oficina, y el segundo, la cuenta. Cada uno
se calcula así:

1. Multiplica los dígitos, de izquierda a derecha, por `1, 2, 1, 2, …`.
2. Si un producto tiene dos cifras, suma sus cifras (`14` cuenta como `1 + 4`).
3. Suma todo. El dígito de control es lo que falta para la siguiente decena:
   `(10 − suma mod 10) mod 10`.

Para `002-191-000123456789-57`:

```
002191        → 0 + 0 + 2 + 2 + 9 + 2 = 15               → 5
000123456789  → 0+0+0+2+2+6+4+(1+0)+6+(1+4)+8+(1+8) = 43  → 7
```

No hay una especificación pública del algoritmo. Se comprobó con 11 CCI que
empresas publican para recibir pagos, de BCP, BBVA, Interbank, Banco de la
Nación, Caja Arequipa y Caja Piura: coinciden los 22 dígitos de control. Solo se
valida el formato: el paquete no sabe si la cuenta existe ni si el código de
entidad está asignado.

## Comparación con otros paquetes

| Paquete | Qué cubre | Diferencia |
|---|---|---|
| `consulta/laravel` | RUC y DNI | Consulta un servicio externo; este paquete funciona sin conexión |
| `esolutions/peru` | Dígito verificador del RUC, formato del DNI | Este paquete agrega reglas de Laravel en objeto y en texto, con mensajes traducidos |

## Desarrollo

Todo corre en Docker con la imagen oficial `composer:2`, así que no hace falta
tener PHP instalado:

```bash
make install   # dependencias
make test      # Pest
make analyse   # PHPStan
make lint      # Pint, sin cambiar archivos
make help      # todos los comandos
```

El CI prueba con Laravel 12 y 13, con PHP 8.2 a 8.5, y también con las versiones
mínimas de las dependencias.

Los cambios de cada versión están en el [CHANGELOG](CHANGELOG.md).

## Licencia

MIT. Ver [LICENSE.md](LICENSE.md).
