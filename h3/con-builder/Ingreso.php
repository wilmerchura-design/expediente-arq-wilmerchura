<?php

namespace Parcial3\Parqueo;

use DateTime;

class Ingreso
{
    public int $idIngreso;
    public Vehiculo $vehiculo;
    public EspacioParqueo $espacio;
    public DateTime $fechaEntrada;
    public ?DateTime $fechaSalida = null;
    public float $horasTotales = 0;

    public function __construct(int $idIngreso, Vehiculo $vehiculo, EspacioParqueo $espacio, DateTime $fechaEntrada)
    {
        $this->idIngreso = $idIngreso;
        $this->vehiculo = $vehiculo;
        $this->espacio = $espacio;
        $this->fechaEntrada = $fechaEntrada;
    }

    public function calcularTiempo(): float
    {
        if ($this->fechaSalida === null) {
            return 0;
        }
        $segundos = $this->fechaSalida->getTimestamp() - $this->fechaEntrada->getTimestamp();
        return round($segundos / 3600, 2);
    }
}
