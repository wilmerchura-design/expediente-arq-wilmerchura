<?php

require_once __DIR__ . '/Vehiculo.php';
require_once __DIR__ . '/Auto.php';
require_once __DIR__ . '/Moto.php';
require_once __DIR__ . '/Camion.php';
require_once __DIR__ . '/VehiculoFactory.php';

use Parcial3\Parqueo\AutoFactory;
use Parcial3\Parqueo\MotoFactory;

$factory = new AutoFactory();
$v1 = $factory->crearVehiculo(1, "1234-ABC", "Toyota", "Rojo");
echo $v1->describir() . PHP_EOL;

$factory = new MotoFactory();
$v2 = $factory->crearVehiculo(2, "5678-XYZ", "Honda", "Negro");
echo $v2->describir() . PHP_EOL;
