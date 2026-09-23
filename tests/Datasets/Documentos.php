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
    'moto: dígitos y dos letras' => ['2171-AY', '2171-AY'],
    'moto: dígitos, dígito y letra' => ['5040-6C', '5040-6C'],
    'moto: dígitos y letra, dígito' => ['1234-A1', '1234-A1'],
    'moto: sin guion' => ['2171ay', '2171-AY'],
    'moto: letra, dígito y dígitos' => ['C5-4481', 'C5-4481'],
    'moto: dos letras y dígitos' => ['ab-1234', 'AB-1234'],
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
    'moto: sin letras' => '2171-56',
    'moto: tres caracteres al final' => '2171-AYZ',
    'moto: cinco dígitos' => '21712-AY',
    'moto: dos dígitos al inicio' => '12-4481',
]);

// CCI sintéticos: códigos de entidad reales con cuentas inventadas y los
// dígitos de control calculados.
dataset('ccis validos', [
    'BCP' => ['00219100012345678957', '00219100012345678957'],
    'BBVA' => ['01110000020012345652', '01110000020012345652'],
    'Interbank' => ['00320000300011122233', '00320000300011122233'],
    'Banco de la Nación' => ['01800000012312312302', '01800000012312312302'],
    'Scotiabank' => ['00910000011122233392', '00910000011122233392'],
    'Caja Arequipa' => ['80310000055566677771', '80310000055566677771'],
    'control 0 en la cuenta' => ['00219100000000000050', '00219100000000000050'],
    'control 0 en entidad y oficina' => ['10000900012345678907', '10000900012345678907'],
    'con guiones' => ['002-191-000123456789-57', '00219100012345678957'],
    'con espacios' => ['002 191 000123456789 57', '00219100012345678957'],
]);

dataset('ccis invalidos', [
    'primer control errado' => '00219100012345678967',
    'segundo control errado' => '00219100012345678958',
    'controles invertidos' => '00219100012345678975',
    'dígitos transpuestos en la cuenta' => '00219100012345687957',
    '19 dígitos' => '0021910001234567895',
    '21 dígitos' => '002191000123456789570',
    'con letras' => '0021910001234567895A',
    'con puntos' => '002.191.000123456789.57',
]);
