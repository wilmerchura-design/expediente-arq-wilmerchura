<?php

namespace Parcial3\Parqueo;

class Vehiculo
{
    public int $idVehiculo;
    public string $placa;
    public string $tipo;   // "Auto", "Moto", "Camion" (como texto simple, sin polimorfismo)
    public string $marca;
    public string $color;

    public function __construct(int $idVehiculo, string $placa, string $tipo, string $marca, string $color)
    {
        $this->idVehiculo = $idVehiculo;
        $this->placa = $placa;
        $this->tipo = $tipo;
        $this->marca = $marca;
        $this->color = $color;
    }

    public function describir(): string
    {
        return "{$this->tipo} {$this->marca} ({$this->color}) - Placa {$this->placa}";
    }
}
