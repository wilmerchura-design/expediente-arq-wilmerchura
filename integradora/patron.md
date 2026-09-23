# Parte 3 — El patrón

## Requerimiento que lo pide a gritos

> "Cuando un pedido queda preparado, el estudiante debe recibir un aviso."

Es un requerimiento de **notificación reactiva ante un cambio de estado**, con una fuente clara
(el pedido) y uno o más interesados en enterarse (el estudiante, y potencialmente más canales a
futuro: la pantalla del mostrador, una app, un mail). Eso es exactamente el problema que resuelve
**Observer**.

## Patrón aplicado: Observer

**Idea**: `Pedido` no conoce ni le importa *cómo* se avisa a nadie. Un objeto intermediario
(`NotificadorPedido`, el "sujeto" observado) mantiene una lista de observadores. Cuando el pedido
cambia a `PREPARADO`, el notificador recorre esa lista y llama a `actualizar()` en cada uno.
El `Estudiante` implementa `IObservadorPedido` y decide qué hacer con el aviso (mostrarlo, mandarlo
por mail, etc.).

## Diseño (código corto, nombres del comedor)

```php
<?php

interface IObservadorPedido
{
    public function actualizar(Pedido $pedido): void;
}

class NotificadorPedido
{
    /** @var IObservadorPedido[] */
    private array $observadores = [];

    public function suscribir(IObservadorPedido $observador): void
    {
        $this->observadores[] = $observador;
    }

    public function desuscribir(IObservadorPedido $observador): void
    {
        $this->observadores = array_filter(
            $this->observadores,
            fn ($o) => $o !== $observador
        );
    }

    public function notificar(Pedido $pedido): void
    {
        foreach ($this->observadores as $observador) {
            $observador->actualizar($pedido);
        }
    }
}

class Estudiante implements IObservadorPedido
{
    public function __construct(public string $nombre) {}

    public function actualizar(Pedido $pedido): void
    {
        if ($pedido->estado === EstadoPedido::PREPARADO) {
            echo "Aviso a {$this->nombre}: tu pedido #{$pedido->id} está preparado.\n";
        }
    }
}

enum EstadoPedido
{
    case SOLICITADO;
    case PREPARADO;
    case ENTREGADO;
    case ANULADO;
}

class Pedido
{
    public EstadoPedido $estado = EstadoPedido::SOLICITADO;

    public function __construct(
        public int $id,
        private NotificadorPedido $notificador
    ) {}

    public function cambiarEstado(EstadoPedido $nuevo): void
    {
        $this->estado = $nuevo;
        if ($nuevo === EstadoPedido::PREPARADO) {
            $this->notificador->notificar($this);
        }
    }
}
```

## Justificación: por qué ESE y qué pasa sin él

**Por qué Observer**: el requerimiento tiene, por definición, un emisor de eventos (el pedido
cambiando de estado) y receptores que necesitan enterarse sin que el emisor sepa quiénes son ni
cuántos. Observer es exactamente ese desacople: `Pedido`/`NotificadorPedido` no conocen la
implementación concreta de "avisar" — solo la interfaz `IObservadorPedido`.

**El dolor real sin él**: la alternativa ingenua es que `Pedido.CambiarEstado()` llame directo a un
método tipo `EnviarSms(estudiante)` o `EnviarPush(estudiante)`. Eso significa:

- **Viola SRP**: `Pedido` pasa a tener una razón de cambio extra (cómo se notifica), además de
  gestionar su propio ciclo de vida.
- **Viola OCP**: el día que el comedor quiera agregar un canal nuevo (ej. notificación en la
  pantalla del mostrador, o un log para auditoría), hay que **modificar** `Pedido` para agregar
  otro `if`, en vez de simplemente agregar un observador nuevo.
- **Acoplamiento fuerte y difícil de testear**: probar `Pedido` en aislamiento obliga a mockear
  el servicio de SMS/push aunque el test no tenga nada que ver con notificaciones.

Con Observer, agregar un canal nuevo es agregar una clase que implementa `IObservadorPedido` y
suscribirla — cero cambios en `Pedido`.
