# Parte 1 — Diagrama de clases (Comedor Universitario "Sabor Andino")

**Autor:** Wilmer Chura

## Receta de 4 pasos

**1. Sustantivos** (candidatos a clase): estudiante, pedido, menú, tipo de menú, cajero, administrador,
estado del pedido, aviso, reporte.

**2. Verbos** (candidatos a método): pedir menú, registrar pedido, cambiar estado del pedido,
ajustar precio, anular pedido, notificar/avisar al estudiante, generar reporte de ventas.

**3. Filtro**: se descartan sustantivos que son solo atributos de otra clase (ej. "tipo de menú" pasa
a ser un enum dentro de `Menu`, no una clase propia). Se agrupan Cajero y Administrador bajo una
clase base `Usuario` porque comparten identidad y difieren en permisos/acciones.

**4. Relaciones**: un `Pedido` pertenece a un `Estudiante` (1 a muchos); un `Pedido` se compone de uno
o más `ItemPedido`, cada uno referenciando un `Menu`; el `Cajero` registra pedidos, el `Administrador`
ajusta precios y anula pedidos; cuando un `Pedido` pasa a `PREPARADO`, un `NotificadorPedido` (patrón
Observer, ver `patron.md`) avisa al `Estudiante`; `ReporteVentas` consulta los `Pedido` para agrupar
por tipo de menú.

## Diagrama

```mermaid
classDiagram
    note "Autor: Wilmer Chura"
    class Usuario {
        <<abstract>>
        +id: int
        +nombre: string
    }
    class Estudiante {
        +codigo: string
        +recibirAviso(mensaje: string)
    }
    class Cajero {
        +registrarPedido(pedido: Pedido)
    }
    class Administrador {
        +ajustarPrecio(menu: Menu, precio: decimal)
        +anularPedido(pedido: Pedido)
    }

    class Pedido {
        +id: int
        +fecha: DateTime
        +estado: EstadoPedido
        +agregarItem(menu: Menu, cantidad: int)
        +cambiarEstado(nuevo: EstadoPedido)
    }
    class ItemPedido {
        +cantidad: int
    }
    class Menu {
        +id: int
        +tipo: TipoMenu
        +precio: decimal
    }
    class TipoMenu {
        <<enumeration>>
        ESTANDAR
        VEGETARIANO
        BECA
    }
    class EstadoPedido {
        <<enumeration>>
        SOLICITADO
        PREPARADO
        ENTREGADO
        ANULADO
    }

    class NotificadorPedido {
        <<Subject - Observer>>
        +suscribir(o: IObservadorPedido)
        +desuscribir(o: IObservadorPedido)
        +notificar(pedido: Pedido)
    }
    class IObservadorPedido {
        <<interface>>
        +actualizar(pedido: Pedido)
    }

    class ReporteVentas {
        +generarPorTipo(desde: DateTime, hasta: DateTime)
    }

    Usuario <|-- Estudiante
    Usuario <|-- Cajero
    Usuario <|-- Administrador

    Estudiante "1" --> "0..*" Pedido : realiza
    Pedido "1" *-- "1..*" ItemPedido : contiene
    ItemPedido "*" --> "1" Menu : referencia
    Menu --> TipoMenu
    Pedido --> EstadoPedido

    Cajero ..> Pedido : registra
    Administrador ..> Pedido : anula
    Administrador ..> Menu : ajusta precio

    Pedido --> NotificadorPedido : dispara aviso
    NotificadorPedido o-- IObservadorPedido : mantiene lista de
    Estudiante ..|> IObservadorPedido : implementa

    ReporteVentas ..> Pedido : consulta
```

## Coherencia con la Parte 3 (patrón)

Las clases `NotificadorPedido` e `IObservadorPedido` en este diagrama **son** el patrón Observer
aplicado en `patron.md`: `Pedido` es el sujeto observado (indirectamente, a través de
`NotificadorPedido`) y `Estudiante` es el observador concreto que reacciona cuando el pedido
cambia a `PREPARADO`.
