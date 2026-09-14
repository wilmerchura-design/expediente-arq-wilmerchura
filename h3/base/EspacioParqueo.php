<?php
// H3 - BASE (sin patrones)

namespace Parcial3\Parqueo;

class EspacioParqueo
{
    public int $idEspacio;
    public int $numero;
    public int $nivel;
    public string $estado; // "DISPONIBLE", "OCUPADO", "RESERVADO", "MANTENIMIENTO"

    public function __construct(int $idEspacio, int $numero, int $nivel, string $estado)
    {
        $this->idEspacio = $idEspacio;
        $this->numero = $numero;
        $this->nivel = $nivel;
        $this->estado = $estado;
    }

    public function ocupar(): void
    {
        $this->estado = "OCUPADO";
    }

    public function liberar(): void
    {
        $this->estado = "DISPONIBLE";
    }
}
