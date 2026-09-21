# H4 — (Sistema de Parqueo )

## C4 — Nivel 1: Contexto

```mermaid
flowchart TD
    Cliente["Cliente<br/>Ingresa y retira su vehiculo, paga"]
    Operador["Operador de caseta<br/>Registra ingresos, salidas y cobros"]
    Sistema["SISTEMA DE PARQUEO<br/>Gestiona vehiculos, espacios, tarifas, tickets y pagos"]
    Pasarela["Pasarela de pago externa<br/>Procesa cobros con tarjeta"]
    Lector["Lector de placas externo<br/>Reconoce la placa automaticamente"]

    Cliente -- "Ingresa/retira, paga" --> Sistema
    Operador -- "Registra ingresos y cobra" --> Sistema
    Sistema -- "Envia el cobro" --> Pasarela
    Sistema -- "Solicita lectura de placa" --> Lector

    classDef persona fill:#08427b,color:#fff,stroke:#052e56;
    classDef sistema fill:#1168bd,color:#fff,stroke:#0b4884;
    classDef externo fill:#999999,color:#fff,stroke:#6b6b6b;

    class Cliente,Operador persona;
    class Sistema sistema;
    class Pasarela,Lector externo;
```

## C4 — Nivel 2: Contenedores

```mermaid
flowchart TD
    Cliente["Cliente"]
    Operador["Operador de caseta"]

    subgraph Sistema["SISTEMA DE PARQUEO"]
        App["Aplicacion de caseta [PHP]<br/>Registra ingresos, salidas y solicita el cobro"]
        Costo["Modulo de Calculo de Costos [PHP]<br/>AQUI viven Strategy (tarifa por vehiculo)<br/>+ Decorator (lavado, valet, seguro)"]
        Ticket["Modulo de Tickets [PHP]<br/>Genera y valida el ticket de ingreso"]
        DB[("Base de datos [MySQL]<br/>Vehiculos, espacios, ingresos, pagos")]
    end

    Pasarela["Pasarela de pago externa"]
    Lector["Lector de placas externo"]

    Cliente --> App
    Operador --> App
    App -- "Pide el costo final" --> Costo
    App -- "Genera el ticket" --> Ticket
    App -- "Lee / escribe" --> DB
    App -- "Cobra con tarjeta (via Adapter)" --> Pasarela
    App -- "Lee la placa (via Adapter)" --> Lector

    classDef persona fill:#08427b,color:#fff,stroke:#052e56;
    classDef contenedor fill:#438dd5,color:#fff,stroke:#2e6295;
    classDef bd fill:#438dd5,color:#fff,stroke:#2e6295;
    classDef externo fill:#999999,color:#fff,stroke:#6b6b6b;

    class Cliente,Operador persona;
    class App,Costo,Ticket contenedor;
    class DB bd;
    class Pasarela,Lector externo;
```

El **Módulo de Cálculo de Costos** es donde conviven los dos patrones
fusionados en `h3/final/`: `ServicioBasico` usa una `CalculadoraTarifa`
(Strategy) para el costo base según el tipo de vehículo, y los decoradores
(`ConLavado`, `ConValet`, `ConSeguroAdicional`) envuelven ese resultado para
sumar cualquier combinación de servicios adicionales.

