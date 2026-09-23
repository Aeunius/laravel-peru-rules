<?php

// Mensajes de las reglas del paquete. Se publican con:
//   php artisan vendor:publish --tag=peru-rules-translations

return [
    'ruc' => 'El campo :attribute debe ser un RUC válido.',
    'ruc_natural' => 'El campo :attribute debe ser un RUC de persona natural válido (empieza con 10).',
    'ruc_juridica' => 'El campo :attribute debe ser un RUC de persona jurídica válido (empieza con 20).',
    'dni' => 'El campo :attribute debe ser un DNI válido de 8 dígitos.',
    'carne_extranjeria' => 'El campo :attribute debe ser un carné de extranjería válido (hasta 12 letras o números).',
    'pasaporte' => 'El campo :attribute debe ser un pasaporte válido (hasta 12 letras o números).',
    'documento' => 'El campo :attribute debe ser un número de :tipo válido (hasta :max letras o números).',
    'documento_tipo' => 'El campo :attribute no se puede validar porque el tipo de documento no es válido.',
    'celular' => 'El campo :attribute debe ser un celular válido de 9 dígitos que empiece con 9.',
    'placa_vehicular' => 'El campo :attribute debe ser una placa vehicular válida (por ejemplo, ABC-123).',
];
