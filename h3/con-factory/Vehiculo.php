<?php



namespace Parcial3\Parqueo;

abstract class Vehiculo
{
    public int $idVehiculo;
    public string $placa;
    public string $marca;
    public string $color;

    public function __construct(int $idVehiculo, string $placa, string $marca, string $color)
    {
        $this->idVehiculo = $idVehiculo;
        $this->placa = $placa;
        $this->marca = $marca;
        $this->color = $color;
    }

    abstract public function getTipo(): string;

    public function describir(): string
    {
        return "{$this->getTipo()} {$this->marca} ({$this->color}) - Placa {$this->placa}";
    }
}
