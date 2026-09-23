<?php

dataset('celulares validos', [
    'solo dígitos' => ['987654321', '987654321'],
    'con +51 y espacios' => ['+51 987 654 321', '987654321'],
    'con 51' => ['51987654321', '987654321'],
    'con guiones' => ['987-654-321', '987654321'],
    'con +51 y guiones' => ['+51-987-654-321', '987654321'],
    'con puntos' => ['987.654.321', '987654321'],
    'con paréntesis' => ['(+51) 987654321', '987654321'],
]);

dataset('celulares invalidos', [
    'fijo de Lima' => '014567890',
    'no empieza con 9' => '887654321',
    '8 dígitos' => '98765432',
    '10 dígitos' => '9876543210',
    'otro código de país' => '+56987654321',
    'con letras' => '98765432A',
    '+ sin código' => '+987654321',
]);

dataset('placas validas', [
    'con guion' => ['ABC-123', 'ABC-123'],
    'sin guion' => ['ABC123', 'ABC-123'],
    'con dígito en el medio' => ['A1B-234', 'A1B-234'],
    'dos dígitos' => ['F55-597', 'F55-597'],
    'minúsculas' => ['abc-123', 'ABC-123'],
    'con espacios alrededor' => [' ABC-123 ', 'ABC-123'],
    'Estado' => ['EGA-123', 'EGA-123'],
    'Estado con e minúscula' => ['eGA-123', 'EGA-123'],
    'Estado con la E separada' => ['E GA-123', 'EGA-123'],
    'Estado con e separada y sin guion' => ['e GA123', 'EGA-123'],
    'policía' => ['E PA-123', 'EPA-123'],
    'diplomática' => ['E CD-123', 'ECD-123'],
]);

dataset('placas invalidas', [
    'empieza con dígito' => '1BC-123',
    'solo dígitos' => '123-456',
    'letras al final' => 'ABC-12D',
    'dos caracteres' => 'AB-123',
    'cuatro dígitos' => 'ABC-1234',
    'con espacio en medio' => 'ABC 123',
    'dos guiones' => 'ABC--123',
    'con ñ' => 'ÑBC-123',
    'espacio tras otra letra' => 'A BC-123',
    'dos espacios tras la E' => 'E  GA-123',
    'E separada de un dígito' => 'E G1-123',
]);
