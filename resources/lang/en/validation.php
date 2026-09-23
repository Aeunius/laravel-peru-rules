<?php

// Mensajes de las reglas del paquete. Se publican con:
//   php artisan vendor:publish --tag=peru-rules-translations

return [
    'ruc' => 'The :attribute field must be a valid RUC.',
    'ruc_natural' => 'The :attribute field must be a valid RUC for an individual (starting with 10).',
    'ruc_juridica' => 'The :attribute field must be a valid RUC for a company (starting with 20).',
    'dni' => 'The :attribute field must be a valid 8-digit DNI.',
    'carne_extranjeria' => 'The :attribute field must be a valid foreigner ID card number (up to 12 letters or digits).',
    'pasaporte' => 'The :attribute field must be a valid passport number (up to 12 letters or digits).',
    'documento' => 'The :attribute field must be a valid :tipo number (up to :max letters or digits).',
    'documento_tipo' => 'The :attribute field cannot be validated because the document type is invalid.',
    'celular' => 'The :attribute field must be a valid 9-digit mobile number starting with 9.',
    'placa_vehicular' => 'The :attribute field must be a valid license plate (for example, ABC-123 or 2171-AY).',
    'cci' => 'The :attribute field must be a valid 20-digit CCI.',
];
