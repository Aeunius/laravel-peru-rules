<?php

// Los casos están en tests/fixtures/ruc.json. Los jurídicos son de entidades
// públicas y empresas conocidas, tomados de la consulta RUC de la SUNAT. Los de
// persona natural (10) y los prefijos 15, 16 y 17 son sintéticos: se arman con
// el algoritmo para no publicar datos de personas.

$ruc = casos('ruc');

dataset('rucs juridicos', $ruc['juridicos']);
dataset('rucs validos', array_merge($ruc['juridicos'], $ruc['naturales'], $ruc['especiales']));
dataset('rucs invalidos', $ruc['invalidos']);
