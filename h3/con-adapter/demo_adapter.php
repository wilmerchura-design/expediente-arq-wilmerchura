<?php
// H3 - CON ADAPTER - Demostracion
require_once __DIR__ . '/LectorPlacaExterno.php';
require_once __DIR__ . '/ILectorPlaca.php';
require_once __DIR__ . '/LectorPlacaAdapter.php';

use Parcial3\Parqueo\LectorPlacaAdapter;
use Parcial3\Parqueo\Externo\LectorPlacaExterno;

$lector = new LectorPlacaAdapter(new LectorPlacaExterno());
$placa = $lector->obtenerPlaca();
echo "Placa detectada: {$placa}" . PHP_EOL;
