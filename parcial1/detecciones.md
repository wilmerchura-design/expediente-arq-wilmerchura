 Detecciones (Variante A — Farmacia)

| # | Principio violado | Dónde vive (clase / método) | Por qué viola el principio |
|---|---|---|---|
| 1 | **ISP** (Segregación de interfaces) | `IEmpleadoDeFarmacia` → implementada por `Cajero` (métodos `AutorizarVentaControlada`, `AjustarPrecio`, `VerLibroDeControlados`) | La interfaz obliga a todo empleado a tener 4 métodos, pero `Cajero` no usa 3 de ellos y los implementa lanzando `NotSupportedException`. Un cliente de la interfaz que solo necesita registrar pedidos igual "carga" con métodos que no le sirven. |
| 2 | **SRP** (Responsabilidad única) | `GestorDePedidos.ProcesarPedido` | Este único método calcula el total, calcula el descuento, guarda el pedido en base de datos, imprime el comprobante por consola Y envía un correo. Son al menos 4 razones distintas para que este método cambie. |
| 3 | **OCP** (Abierto/cerrado) | `GestorDePedidos.ProcesarPedido` (switch sobre `tipoCliente`) | Si mañana aparece un nuevo tipo de cliente (por ejemplo "empleado"), hay que abrir este método y modificar el switch existente en vez de poder extender el comportamiento sin tocar código que ya funciona. |
| 4 | **DIP** (Inversión de dependencias) | `GestorDePedidos.ProcesarPedido` (`new BaseDeDatosMySql()`, `new CorreoSmtp()`) | El módulo de alto nivel (`GestorDePedidos`) crea directamente instancias concretas de bajo nivel (BD y correo) en vez de depender de una abstracción. Si mañana cambia el motor de base de datos o el proveedor de correo, hay que tocar `GestorDePedidos`. |

**Curadas en `refactor.cs`:** ISP y DIP.
**Detectadas pero no curadas (quedan señaladas):** SRP y OCP.
