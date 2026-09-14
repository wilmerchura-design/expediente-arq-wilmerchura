<?php


namespace Parcial3\Parqueo;

abstract class VehiculoFactory
{
    abstract public function crearVehiculo(int $idVehiculo, string $placa, string $marca, string $color): Vehiculo;
}

class AutoFactory extends VehiculoFactory
{
    public function crearVehiculo(int $idVehiculo, string $placa, string $marca, string $color): Vehiculo
    {
        return new Auto($idVehiculo, $placa, $marca, $color);
    }
}

class MotoFactory extends VehiculoFactory
{
    public function crearVehiculo(int $idVehiculo, string $placa, string $marca, string $color): Vehiculo
    {
        return new Moto($idVehiculo, $placa, $marca, $color);
    }
}

class CamionFactory extends VehiculoFactory
{
    public function crearVehiculo(int $idVehiculo, string $placa, string $marca, string $color): Vehiculo
    {
        return new Camion($idVehiculo, $placa, $marca, $color);
    }
}
