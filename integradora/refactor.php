<?php
// Refactor: Wilmer Chura
//
// Violación curada: SRP (Single Responsibility Principle)
//
// Antes: GestorDePedidos::procesarPedido() calculaba el precio, persistía el pedido,
// imprimía el vale por consola y enviaba un correo, todo en un solo método.
// Ahora: cada responsabilidad vive en su propia clase, con una sola razón de cambio.
// GestorDePedidos pasa a ser un orquestador que delega, no un método que hace de todo.

declare(strict_types=1);

namespace Integradora\Comedor;

// Responsabilidad única: calcular el precio de un pedido según el tipo de menú.
// Si cambian las reglas de precios, solo se toca esta clase.
class CalculadoraPrecioMenu
{
    public function calcular(string $tipoMenu, int $cantidad): float
    {
        $precioBase = match ($tipoMenu) {
            'estandar' => 12,
            'vegetariano' => 14,
            'beca' => 5,
            default => 12,
        };

        return $precioBase * $cantidad;
    }
}

// Responsabilidad única: dar formato e imprimir el vale del pedido.
// Si cambia cómo se presenta el vale al estudiante, solo se toca esta clase.
class ImpresorDeVale
{
    public function imprimir(string $estudiante, string $tipoMenu, int $cantidad, float $total): void
    {
        echo "----- VALE DE COMEDOR -----\n";
        echo "{$estudiante}: {$cantidad} x menú {$tipoMenu}\n";
        echo 'TOTAL: ' . number_format($total, 2) . " Bs\n";
    }
}

// Responsabilidad única: persistir el pedido. (Sin cambios respecto al esqueleto original;
// la violación de DIP sobre esta clase queda documentada en detecciones.md, fuera de este refactor.)
class BaseDeDatosComedor
{
    public function guardarPedido(string $estudiante, string $menu, int $cantidad, float $total): void
    {
        echo "[BD] INSERT INTO pedidos VALUES ('{$estudiante}', '{$menu}', {$cantidad}, {$total})\n";
    }
}

// Responsabilidad única: notificar por correo. (Sin cambios respecto al esqueleto original.)
class CorreoUniversitario
{
    public function enviar(string $mensaje): void
    {
        echo "[CORREO] {$mensaje}\n";
    }
}

// Orquestador: coordina el flujo del pedido delegando cada paso a la clase responsable.
// Su única razón de cambio ahora es el ORDEN o la LÓGICA del flujo de negocio, no el cálculo,
// la persistencia, la impresión ni el envío de correo por separado.
class GestorDePedidos
{
    private CalculadoraPrecioMenu $calculadora;
    private ImpresorDeVale $impresor;
    private BaseDeDatosComedor $baseDeDatos;
    private CorreoUniversitario $correo;

    public function __construct()
    {
        $this->calculadora = new CalculadoraPrecioMenu();
        $this->impresor = new ImpresorDeVale();
        $this->baseDeDatos = new BaseDeDatosComedor();
        $this->correo = new CorreoUniversitario();
    }

    public function procesarPedido(string $estudiante, string $tipoMenu, int $cantidad): void
    {
        $total = $this->calculadora->calcular($tipoMenu, $cantidad);

        $this->baseDeDatos->guardarPedido($estudiante, $tipoMenu, $cantidad, $total);
        $this->impresor->imprimir($estudiante, $tipoMenu, $cantidad, $total);

        $this->correo->enviar("Pedido registrado: {$cantidad} x {$tipoMenu}, {$estudiante}");
    }
}

// Demo
(new GestorDePedidos())->procesarPedido('Noelia', 'vegetariano', 2);
