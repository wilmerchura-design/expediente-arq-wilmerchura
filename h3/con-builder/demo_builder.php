<?php

require_once __DIR__ . '/Vehiculo.php';
require_once __DIR__ . '/EspacioParqueo.php';
require_once __DIR__ . '/Ingreso.php';
require_once __DIR__ . '/IngresoBuilder.php';

use Parcial3\Parqueo\Vehiculo;
use Parcial3\Parqueo\EspacioParqueo;
use Parcial3\Parqueo\IngresoBuilder;

$vehiculo = new Vehiculo(1, "1234-ABC", "Auto", "Toyota", "Rojo");
$espacio = new EspacioParqueo(1, 12, 1, "DISPONIBLE");

$ingreso = (new IngresoBuilder())
    ->conId(100)
    ->conVehiculo($vehiculo)
    ->conEspacio($espacio)
    ->conFechaEntrada(new DateTime())
    ->build();

echo "Ingreso #{$ingreso->idIngreso} - {$ingreso->vehiculo->describir()} - Espacio {$ingreso->espacio->numero}" . PHP_EOL;
