# Laravel Peru Rules

Reglas de validación de Laravel para documentos peruanos: RUC y DNI, por ahora.
Validan el formato y el dígito verificador sin conectarse a ningún servicio externo.

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
use Aeunius\PeruRules\Rules\Dni;
use Aeunius\PeruRules\Rules\Ruc;

$request->validate([
    'ruc'         => ['required', new Ruc],
    'ruc_empresa' => ['required', Ruc::juridica()],
    'dni'         => ['required', new Dni],
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
