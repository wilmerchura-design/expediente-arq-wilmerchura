# Variante B Gimnasio "Fuerza Andina"

## P2.1 — Elegir y justificar

### Situación 1 — Aviso de membresía vencida → Patrón: **Observer**

Hoy el módulo de socios conoce y llama uno por uno a cada interesado
(WhatsApp, registro de vencidos, recepción); cada vez que aparece un
interesado nuevo (promociones, y "quien sabe qué más") hay que abrir ese
módulo y agregarle una llamada más. Con Observer, el módulo de socios solo
emite el evento "membresía vencida" y cada interesado se suscribe por su
cuenta como un observador; agregar promociones (o cualquier interesado
futuro) ya no toca el módulo de socios. Si no se aplica, cada nuevo
interesado obliga a modificar código que ya funciona (rompe OCP) y acopla al
módulo de socios con los detalles de WhatsApp, recepción, etc.

### Situación 2 — Tarifa por franja horaria → Patrón: **Strategy**

El cálculo de tarifa cambia según la franja (mañana, noche, fin de semana) y
hoy vive duplicado como if/else en dos módulos (cobros y cotizaciones); cada
cambio de temporada obliga a tocar ambos lugares y mantenerlos
sincronizados a mano. Con Strategy, cada franja es una clase independiente
que implementa el mismo contrato, y ambos módulos comparten la misma
calculadora sin duplicar la lógica. Si no se aplica, el riesgo real es que
un cambio de temporada se actualice en un módulo y se olvide en el otro,
generando cobros y cotizaciones inconsistentes entre sí.

### Situación 3 — SDK de pasarela de pago externa → Patrón: **Adapter**

El SDK del proveedor expone un contrato ajeno al dominio (nombres en
inglés, montos en centavos, tokens propios) y no se puede modificar;
además el proveedor podría cambiar el próximo año. Con Adapter, se crea una
clase que traduce el contrato del proveedor a una interfaz propia del
dominio (por ejemplo `IProcesadorDePago`), de modo que si cambia el
proveedor solo se reemplaza el adapter, no el resto del sistema. Si no se
aplica, el dominio terminaría lleno de conceptos ajenos (`amountCents`,
`customerToken`) y un cambio de proveedor obligaría a modificar el cobro en
todo el sistema, no en un solo lugar.

---

## P2.2 — Implementada: Situación 2 (Strategy)

Ver `solucion.php`: contrato `CalculadoraTarifa`, dos piezas concretas como
mínimo (`TarifaManana`, `TarifaNoche`, `TarifaFinDeSemana` — se hicieron las
3 franjas reales del enunciado) y el contexto `CalculadoraDeCobro`, usado
tanto por "cobros" como por "cotizaciones" con los nombres del dominio del
gimnasio.

---

## P2.3 — La conexión SOLID

La implementación de Strategy en `solucion.php` rescata el **OCP (Principio
Abierto/Cerrado)**: `CalculadoraDeCobro` no cambia su código para soportar
una franja horaria nueva (por ejemplo, una futura promoción de verano) —
solo se agrega una clase nueva que implemente `CalculadoraTarifa`. Esto se
ve concretamente en el constructor de `CalculadoraDeCobro`, que recibe la
interfaz `CalculadoraTarifa` (no una clase concreta), y en el método
`cambiarEstrategia()`, que permite sustituir el algoritmo de cálculo sin
tocar ninguna otra línea de la clase.
