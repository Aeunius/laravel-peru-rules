# Casos de prueba compartidos

Estos JSON son la única fuente de los casos de prueba de los algoritmos. Los usan
los datasets de Pest (`tests/Datasets`) y también el paquete de JavaScript
[`peru-rules-js`](https://github.com/Aeunius/peru-rules-js), que los descarga
desde una etiqueta de este repositorio. Así las dos versiones dan el mismo
resultado con los mismos casos.

Cada caso tiene un nombre que lo describe:

| Archivo | Contenido |
|---|---|
| `ruc.json` | `juridicos`, `naturales` y `especiales` (válidos) e `invalidos`: `nombre → ruc` |
| `dni.json` | `validos` e `invalidos`: `nombre → dni` |
| `celular.json`, `placa.json`, `cci.json` | `validos`: `nombre → [entrada, normalizado]`; `invalidos`: `nombre → entrada` |
| `tipo-documento.json` | `nombre → [código del catálogo 06, número válido, número inválido]` |

Los RUC jurídicos son de entidades públicas y empresas conocidas, tomados de la
consulta RUC de la SUNAT. Los de persona natural y los de prefijos 15, 16 y 17
son sintéticos, para no publicar datos de personas. Los CCI también son
sintéticos: códigos de entidad reales con cuentas inventadas y los dígitos de
control calculados.

Un cambio aquí es un cambio de comportamiento: agrégalo al CHANGELOG.
