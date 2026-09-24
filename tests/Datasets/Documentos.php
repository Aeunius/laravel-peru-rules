<?php

// Los casos están en tests/fixtures/*.json. Los CCI son sintéticos: códigos de
// entidad reales con cuentas inventadas y los dígitos de control calculados.

$dni = casos('dni');
$celular = casos('celular');
$placa = casos('placa');
$cci = casos('cci');

dataset('dnis validos', $dni['validos']);
dataset('dnis invalidos', $dni['invalidos']);
dataset('celulares validos', $celular['validos']);
dataset('celulares invalidos', $celular['invalidos']);
dataset('placas validas', $placa['validos']);
dataset('placas invalidas', $placa['invalidos']);
dataset('ccis validos', $cci['validos']);
dataset('ccis invalidos', $cci['invalidos']);
dataset('tipos de documento', casos('tipo-documento'));
