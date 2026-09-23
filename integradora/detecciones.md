# Parte 2 — Detecciones SOLID (esqueleto-A, adaptado a PHP)

## 1. SRP (Single Responsibility Principle)

**Dónde:** `GestorDePedidos::procesarPedido()` completo.

**Por qué:** un solo método hace cuatro trabajos distintos que cambian por razones distintas:
calcula el precio, persiste el pedido (`BaseDeDatosComedor`), imprime el vale por consola y envía
un correo (`CorreoUniversitario`). Si cambia el formato del vale, la forma de notificar o la regla
de precios, hay que tocar la misma clase por motivos que no tienen nada que ver entre sí.

## 2. OCP (Open/Closed Principle)

**Dónde:** el `switch`/`match` sobre `$tipoMenu` dentro de `procesarPedido()`, con los precios
hardcodeados como literales (`12`, `14`, `5`) y `$tipoMenu` como magic string.

**Por qué:** el día que el comedor agregue un cuarto tipo de menú (o cambie un precio), la única
forma de hacerlo es **modificar** este método y agregar otro `case`. La clase no está cerrada a
modificación ni abierta a extensión: no hay forma de agregar un tipo de menú sin tocar código ya
existente y probado.

## 3. DIP (Dependency Inversion Principle)

**Dónde:** dentro de `procesarPedido()`, las líneas `$baseDeDatos = new BaseDeDatosComedor();`
y `$correo = new CorreoUniversitario();`.

**Por qué:** `GestorDePedidos` (módulo de alto nivel, la lógica de negocio) depende directamente de
clases concretas de infraestructura (persistencia y correo) en vez de depender de abstracciones.
Esto impide sustituir la base de datos o el canal de correo (por ejemplo en un test, con un mock)
sin modificar `GestorDePedidos`.

---

*Se curó en código la violación de **SRP** (ver `refactor.php`). OCP y DIP quedan documentadas acá
pero fuera del alcance del refactor pedido (una de las tres).*
