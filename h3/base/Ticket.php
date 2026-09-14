<?php
// H3 - BASE (sin patrones)

namespace Parcial3\Parqueo;

use DateTime;

class Ticket
{
    public int $idTicket;
    public string $codigo;
    public DateTime $fechaEmision;

    public function __construct(int $idTicket, string $codigo, DateTime $fechaEmision)
    {
        $this->idTicket = $idTicket;
        $this->codigo = $codigo;
        $this->fechaEmision = $fechaEmision;
    }

    public function imprimir(): void
    {
        echo "[TICKET] {$this->codigo} - Emitido: " . $this->fechaEmision->format('Y-m-d H:i:s') . PHP_EOL;
    }
}
