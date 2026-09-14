<?php


namespace Parcial3\Parqueo;

use DateTime;

class Pago
{
    public int $idPago;
    public float $monto;
    public DateTime $fecha;
    public string $estado;
    public string $metodoPago; // "Efectivo", "Tarjeta", "QR" (texto simple, sin patrones)

    public function __construct(int $idPago, float $monto, DateTime $fecha, string $metodoPago)
    {
        $this->idPago = $idPago;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->metodoPago = $metodoPago;
        $this->estado = "PENDIENTE";
    }

    public function registrar(): void
    {
        $this->estado = "PAGADO";
    }
}
