<?php
declare(strict_types=1);

//  VARIANTE B  Gimnasio "Fuerza Andina"
// Situacion 2: la tarifa por hora se calcula distinto segun la franja
// (manana, noche, fin de semana) y ese calculo estaba duplicado como
// if/else en el modulo de cobros y en el de cotizaciones.
//
// Solucion: Wilmer Chura
// Patron aplicado: STRATEGY.
// Cada franja horaria es una clase independiente que implementa el mismo
// contrato (CalculadoraTarifa). El modulo de cobros y el de cotizaciones
// usan el mismo contexto (CalculadoraDeCobro) sin duplicar logica, y
// agregar una franja nueva no obliga a tocar el codigo existente.

namespace Parcial2\Gimnasio;

//  Contrato (Strategy) 
interface CalculadoraTarifa
{
    public function calcular(float $tarifaBase, float $horas): float;
}

//  Piezas concretas 

class TarifaManana implements CalculadoraTarifa
{
    // Tarifa plena
    public function calcular(float $tarifaBase, float $horas): float
    {
        return $tarifaBase * $horas;
    }
}

class TarifaNoche implements CalculadoraTarifa
{
    // Recargo del 20% por demanda
    public function calcular(float $tarifaBase, float $horas): float
    {
        return $tarifaBase * $horas * 1.20;
    }
}

class TarifaFinDeSemana implements CalculadoraTarifa
{
    // Descuento del 30%, con tope de 3 horas cobrables
    public function calcular(float $tarifaBase, float $horas): float
    {
        $horasCobradas = min($horas, 3.0);
        return $tarifaBase * $horasCobradas * 0.70;
    }
}

// ---------- Contexto: lo usan tanto "cobros" como "cotizaciones" ----------
class CalculadoraDeCobro
{
    private CalculadoraTarifa $estrategia;

    public function __construct(CalculadoraTarifa $estrategia)
    {
        $this->estrategia = $estrategia;
    }

    public function cambiarEstrategia(CalculadoraTarifa $estrategia): void
    {
        $this->estrategia = $estrategia;
    }

    public function calcularMonto(float $tarifaBase, float $horas): float
    {
        return $this->estrategia->calcular($tarifaBase, $horas);
    }
}

// Ejemplo de uso con nombres del dominio del gimnasio 

$tarifaBasePorHora = 25.00; // Bs por hora en "Fuerza Andina"

// Modulo de COBROS: un socio reserva 2 horas en horario nocturno
$calculadoraCobros = new CalculadoraDeCobro(new TarifaNoche());
$montoCobroSocio = $calculadoraCobros->calcularMonto($tarifaBasePorHora, 2);
echo "[COBROS] Socio - horario nocturno (2h): Bs " . number_format($montoCobroSocio, 2) . PHP_EOL;

// Modulo de COTIZACIONES: un prospecto pide cotizar 5 horas el fin de semana
// (se le aplica el tope de 3 horas cobrables, igual que en cobros reales)
$calculadoraCotizaciones = new CalculadoraDeCobro(new TarifaFinDeSemana());
$montoCotizacionProspecto = $calculadoraCotizaciones->calcularMonto($tarifaBasePorHora, 5);
echo "[COTIZACIONES] Prospecto - fin de semana (pide 5h, tope 3h): Bs " . number_format($montoCotizacionProspecto, 2) . PHP_EOL;

// La franja de la manana usa la misma calculadora, solo cambia la estrategia:
$calculadoraCobros->cambiarEstrategia(new TarifaManana());
$montoCobroManana = $calculadoraCobros->calcularMonto($tarifaBasePorHora, 1);
echo "[COBROS] Socio - horario de manana (1h): Bs " . number_format($montoCobroManana, 2) . PHP_EOL;
