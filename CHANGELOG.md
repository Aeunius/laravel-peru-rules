# Changelog

Todos los cambios importantes de este paquete se registran aquí.

El formato sigue [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y el
proyecto usa [versionado semántico](https://semver.org/lang/es/).

## [Sin publicar]

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
- Regla `PlacaVehicular` para el formato vigente, con
  `PlacaVehicularValidator::normalizar()`.
- Reglas en texto `carne_extranjeria`, `pasaporte`, `celular`,
  `placa_vehicular` y `documento_identidad:campo`.
- Mensajes en español e inglés, publicables con
  `php artisan vendor:publish --tag=peru-rules-translations`.
