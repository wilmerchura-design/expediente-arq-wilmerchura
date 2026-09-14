<?php


namespace Parcial3\Parqueo;

use Parcial3\Parqueo\Externo\LectorPlacaExterno;

class LectorPlacaAdapter implements ILectorPlaca
{
    private LectorPlacaExterno $externo;

    public function __construct(LectorPlacaExterno $externo)
    {
        $this->externo = $externo;
    }

    public function obtenerPlaca(): string
    {
      
        return strtoupper($this->externo->escanearMatricula());
    }
}
