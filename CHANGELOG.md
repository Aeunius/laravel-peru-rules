# Changelog

Todos los cambios importantes de este paquete se registran aquí.

El formato sigue [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y el
proyecto usa [versionado semántico](https://semver.org/lang/es/).

## [Sin publicar]

### Agregado

- `ValueObjects\Ruc`: un RUC válido como objeto, con `valor()`, `formateado()`,
  `tipo()`, `esNatural()`, `esJuridica()`, `dni()` y `esIgual()`. Se crea con
  `Ruc::from()` o `Ruc::tryFrom()`.
- `Casts\RucCast`, también disponible como `'ruc' => Ruc::class`.
- Enum `TipoContribuyente` (`Natural`, `Juridica`, `Especial`) según el prefijo
  del RUC.

### Cambiado

- **Incompatible:** `isValid()` pasa a llamarse `esValido()` en todos los
  validadores de `Support\` y en `TipoDocumento`, para que la API quede toda en
  español.

### Eliminado

- **Incompatible:** el archivo de configuración `config/peru-rules.php` y la
  etiqueta `peru-rules-config`. No tenía opciones.

## [0.1.0] - 2026-09-23

Primera versión.

### Agregado

- Regla `Ruc`: formato, prefijo y dígito verificador, con las variantes
  `Ruc::natural()` y `Ruc::juridica()`.
- Regla `Dni`: 8 dígitos.
- Reglas en texto `ruc`, `ruc:natural`, `ruc:juridica` y `dni`.
- `Support\RucValidator` y `Support\DniValidator`, sin dependencia de Laravel.
- Reglas `CarneExtranjeria` y `Pasaporte`: hasta 12 letras o números.
- Enum `TipoDocumento` con los códigos del catálogo 06 de la SUNAT, y regla
  `DocumentoIdentidad::segun('campo')` que valida el número según el tipo de otro
  campo, también con comodines en arreglos.
- Regla `Celular`, que acepta `+51` y separadores, con
  `CelularValidator::normalizar()`.
- Regla `PlacaVehicular` para el formato vigente, incluidas las placas especiales
  con prefijo E (Estado, policía, emergencias, diplomáticas), con
  `PlacaVehicularValidator::normalizar()`.
- `PlacaVehicular` acepta también las placas de motos y mototaxis (`2171-AY`,
  `5040-6C`, `C5-4481`).
- Regla `Cci`: 20 dígitos y dígitos de control, con `CciValidator::normalizar()`.
- Reglas en texto `carne_extranjeria`, `pasaporte`, `celular`,
  `placa_vehicular`, `cci` y `documento_identidad:campo`.
- Mensajes en español e inglés, publicables con
  `php artisan vendor:publish --tag=peru-rules-translations`.

[Sin publicar]: https://github.com/Aeunius/laravel-peru-rules/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/Aeunius/laravel-peru-rules/releases/tag/v0.1.0
