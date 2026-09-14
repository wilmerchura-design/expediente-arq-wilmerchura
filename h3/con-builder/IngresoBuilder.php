<?php


namespace Parcial3\Parqueo;

use DateTime;
use InvalidArgumentException;

class IngresoBuilder
{
    private int $idIngreso = 0;
    private ?Vehiculo $vehiculo = null;
    private ?EspacioParqueo $espacio = null;
    private DateTime $fechaEntrada;

    public function __construct()
    {
        $this->fechaEntrada = new DateTime();
    }

    public function conId(int $idIngreso): self
    {
        $this->idIngreso = $idIngreso;
        return $this;
    }

    public function conVehiculo(Vehiculo $vehiculo): self
    {
        $this->vehiculo = $vehiculo;
        return $this;
    }

    public function conEspacio(EspacioParqueo $espacio): self
    {
        $this->espacio = $espacio;
        return $this;
    }

    public function conFechaEntrada(DateTime $fecha): self
    {
        $this->fechaEntrada = $fecha;
        return $this;
    }

    public function build(): Ingreso
    {
        if ($this->vehiculo === null) {
            throw new InvalidArgumentException("El ingreso necesita un vehiculo.");
        }
        if ($this->espacio === null) {
            throw new InvalidArgumentException("El ingreso necesita un espacio.");
        }

        return new Ingreso($this->idIngreso, $this->vehiculo, $this->espacio, $this->fechaEntrada);
    }
}
