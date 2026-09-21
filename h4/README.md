# H4 — Documentación de la decisión (Sistema de Parqueo "San Rafael")

## C4 — Nivel 1: Contexto

```mermaid
C4Context
    title Sistema de Parqueo de Automóviles (Nivel 1 - Contexto)

    Person(cliente, "Cliente", "Persona que ingresa su vehiculo al parqueo")
    Person(operador, "Operador de caseta", "Registra entradas, salidas y cobros")

    System(sistemaParqueo, "Sistema de Parqueo", "Gestiona vehiculos, espacios, ingresos, tarifas, tickets y pagos")

    System_Ext(pasarelaPago, "Pasarela de pago externa", "Procesa cobros con tarjeta (SDK de un proveedor)")
    System_Ext(lectorPlacas, "Lector de placas", "Hardware/SDK que reconoce automaticamente la placa del vehiculo")

    Rel(cliente, sistemaParqueo, "Ingresa/retira su vehiculo, paga")
    Rel(operador, sistemaParqueo, "Registra ingresos y salidas, cobra")
    Rel(sistemaParqueo, pasarelaPago, "Envia el cobro con tarjeta")
    Rel(sistemaParqueo, lectorPlacas, "Solicita la lectura de la placa")
```

## C4 — Nivel 2: Contenedores

```mermaid
C4Container
    title Sistema de Parqueo de Automóviles (Nivel 2 - Contenedores)

    Person(cliente, "Cliente")
    Person(operador, "Operador de caseta")

    System_Boundary(sistemaParqueo, "Sistema de Parqueo") {
        Container(appCaseta, "Aplicacion de caseta", "PHP", "Registra ingresos, salidas y solicita el cobro")
        Container(moduloCosto, "Modulo de Calculo de Costos", "PHP", "AQUI viven los 2 patrones fusionados: Strategy (tarifa segun tipo de vehiculo) + Decorator (servicios adicionales: lavado, valet, seguro)")
        Container(moduloTicket, "Modulo de Tickets", "PHP", "Genera y valida el ticket de ingreso")
        ContainerDb(baseDatos, "Base de datos", "MySQL", "Guarda vehiculos, espacios, ingresos y pagos")
    }

    System_Ext(pasarelaPago, "Pasarela de pago externa")
    System_Ext(lectorPlacas, "Lector de placas externo")

    Rel(cliente, appCaseta, "Usa")
    Rel(operador, appCaseta, "Usa")
    Rel(appCaseta, moduloCosto, "Pide el costo final del ingreso (tarifa + servicios)")
    Rel(appCaseta, moduloTicket, "Genera el ticket")
    Rel(appCaseta, baseDatos, "Lee / escribe")
    Rel(appCaseta, pasarelaPago, "Cobra con tarjeta (via Adapter, h3/con-adapter)")
    Rel(appCaseta, lectorPlacas, "Lee la placa (via Adapter, h3/con-adapter)")
```

El **Módulo de Cálculo de Costos** es donde conviven los dos patrones
fusionados en `h3/final/`: `ServicioBasico` usa una `CalculadoraTarifa`
(Strategy) para el costo base según el tipo de vehículo, y los decoradores
(`ConLavado`, `ConValet`, `ConSeguroAdicional`) envuelven ese resultado para
sumar cualquier combinación de servicios adicionales.
