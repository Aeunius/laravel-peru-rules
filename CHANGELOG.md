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
- Mensajes en español e inglés, publicables con
  `php artisan vendor:publish --tag=peru-rules-translations`.
