<?php

// Mensajes de las reglas del paquete. Se publican con:
//   php artisan vendor:publish --tag=peru-rules-translations

return [
    'ruc' => 'The :attribute field must be a valid RUC.',
    'ruc_natural' => 'The :attribute field must be a valid RUC for an individual (starting with 10).',
    'ruc_juridica' => 'The :attribute field must be a valid RUC for a company (starting with 20).',
    'dni' => 'The :attribute field must be a valid 8-digit DNI.',
];
