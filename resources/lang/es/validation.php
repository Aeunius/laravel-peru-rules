<?php

// Mensajes de las reglas del paquete. Se publican con:
//   php artisan vendor:publish --tag=peru-rules-translations

return [
    'ruc' => 'El campo :attribute debe ser un RUC válido.',
    'ruc_natural' => 'El campo :attribute debe ser un RUC de persona natural válido (empieza con 10).',
    'ruc_juridica' => 'El campo :attribute debe ser un RUC de persona jurídica válido (empieza con 20).',
    'dni' => 'El campo :attribute debe ser un DNI válido de 8 dígitos.',
];
