// VARIANTE A — Farmacia "San Rafael"
// Sistema de pedidos de medicamentos.
// Se curan 2 de las 4 violaciones detectadas en detecciones.md: ISP y DIP.
// SRP y OCP quedan detectadas pero sin curar (no se pedía curar las 4).

namespace Parcial1.Farmacia;

// CURA 1: ISP (Segregación de interfaces)
// Refactor: Wilmer Chura
// Antes, una sola interfaz "gorda" (IEmpleadoDeFarmacia) obligaba a TODO empleado
// a tener 4 métodos, y Cajero implementaba 3 de ellos solo para lanzar
// NotSupportedException. Ahora se separa en dos contratos: uno básico (que
// cualquier empleado cumple de verdad) y uno extendido solo para quien
// realmente autoriza ventas controladas, ajusta precios y ve el libro.

public interface IEmpleadoDeFarmacia
{
    void RegistrarPedido(string medicamento, int cantidad);
}

public interface IEmpleadoFarmaceutico : IEmpleadoDeFarmacia
{
    void AutorizarVentaControlada(string medicamento);
    void AjustarPrecio(string medicamento, decimal nuevoPrecio);
    void VerLibroDeControlados();
}

public class Farmaceutico : IEmpleadoFarmaceutico
{
    public void RegistrarPedido(string medicamento, int cantidad)
        => Console.WriteLine($"[FARM] Pedido: {cantidad} x {medicamento}");

    public void AutorizarVentaControlada(string medicamento)
        => Console.WriteLine($"[FARM] Venta controlada de {medicamento} autorizada");

    public void AjustarPrecio(string medicamento, decimal nuevoPrecio)
        => Console.WriteLine($"[FARM] {medicamento} ahora cuesta {nuevoPrecio:0.00} Bs");

    public void VerLibroDeControlados()
        => Console.WriteLine("[FARM] Libro de medicamentos controlados");
}

public class Cajero : IEmpleadoDeFarmacia
{
    // Ya no implementa AutorizarVentaControlada, AjustarPrecio ni VerLibroDeControlados:
    // ese contrato ya no le pertenece, así que no necesita lanzar excepciones
    // para métodos que no debería tener.
    public void RegistrarPedido(string medicamento, int cantidad)
        => Console.WriteLine($"[CAJA] Pedido: {cantidad} x {medicamento}");
}

// CURA 2: DIP (Inversión de dependencias)
// Refactor: Wilmer Chura
// Antes, GestorDePedidos creaba "new BaseDeDatosMySql()" y "new CorreoSmtp()"
// directamente adentro del método (dependía de clases concretas de bajo nivel).
// Ahora depende de dos abstracciones (contratos) que recibe por constructor.

public interface IRepositorioPedidos
{
    void GuardarPedido(string cliente, string medicamento, int cantidad, decimal total);
}

public interface INotificador
{
    void Enviar(string mensaje);
}

public class BaseDeDatosMySql : IRepositorioPedidos
{
    public void GuardarPedido(string cliente, string medicamento, int cantidad, decimal total)
        => Console.WriteLine($"[MYSQL] INSERT INTO pedidos VALUES ('{cliente}', '{medicamento}', {cantidad}, {total})");
}

public class CorreoSmtp : INotificador
{
    public void Enviar(string mensaje)
        => Console.WriteLine($"[SMTP] {mensaje}");
}

public class GestorDePedidos
{
    private readonly IRepositorioPedidos _repositorio;
    private readonly INotificador _notificador;

    public GestorDePedidos(IRepositorioPedidos repositorio, INotificador notificador)
    {
        _repositorio = repositorio;
        _notificador = notificador;
    }

    //  este método sigue teniendo las violaciones de SRP y OCP
    // (detectadas en detecciones.md
    //     // se pedía curar 2 de las 4.
    public void ProcesarPedido(string cliente, string tipoCliente, string medicamento, int cantidad, decimal precioUnitario)
    {
        decimal total = cantidad * precioUnitario;

        decimal descuento;
        switch (tipoCliente)
        {
            case "particular":
                descuento = 0;
                break;
            case "asegurado":
                descuento = total * 0.20m;
                break;
            case "convenio":
                descuento = total * 0.10m;
                break;
            default:
                descuento = 0;
                break;
        }
        decimal totalFinal = total - descuento;

        _repositorio.GuardarPedido(cliente, medicamento, cantidad, totalFinal);

        Console.WriteLine("----- COMPROBANTE -----");
        Console.WriteLine($"{cantidad} x {medicamento}");
        Console.WriteLine($"Cliente: {cliente} ({tipoCliente})");
        Console.WriteLine($"TOTAL: {totalFinal:0.00} Bs");

        _notificador.Enviar($"Su pedido de {medicamento} fue registrado, {cliente}");
    }
}

public static class Demo
{
    public static void Correr()
    {
        var gestor = new GestorDePedidos(new BaseDeDatosMySql(), new CorreoSmtp());
        gestor.ProcesarPedido("Noelia", "asegurado", "Paracetamol 500mg", 2, 8.50m);
    }
}
