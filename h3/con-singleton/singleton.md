# H3 — Singleton: por qué mi caso NO lo pide

Analicé si el sistema de parqueo necesita alguna clase con una única instancia
global (el candidato más obvio sería algo como "el gestor único de espacios"
o "la configuración del sistema"). Mi conclusión es que **no lo necesito**, y
prefiero no forzarlo. Razones:

1. Ya resolví el problema de fondo con otro patrón, sin los efectos secundarios del Singleton.**
   En el H2 (SOLID) ya introduje `IEspacioRepositorio`, que es el único punto
   de acceso a los datos de los espacios de parqueo. Eso me da la misma
   ventaja que buscaría un Singleton (un solo lugar consistente para leer/
   escribir el estado de los espacios) pero inyectando la dependencia por
   constructor, no accediendo a un estado global estático.

2. El Singleton es un estado global mutable disfrazado.
   Si convirtiera el repositorio o el "gestor de espacios" en un Singleton
   (`EspacioManager.Instancia`), cualquier clase del sistema podría acceder y
   modificar ese estado desde cualquier parte, sin pasar por sus dependencias
   declaradas. Eso rompe justamente el DIP que apliqué en el H2: las clases
   dejarían de depender de una abstracción explícita y pasarían a depender de
   un punto de acceso global oculto.

3. Dificulta las pruebas.
   Con `IEspacioRepositorio` inyectado, puedo probar `EspacioService` con un
   repositorio falso (`EspacioRepositorioFalso`, que ya tengo en el H2) sin
   tocar nada global. Con un Singleton, todas las pruebas comparten la misma
   instancia y el mismo estado, lo que genera pruebas que se afectan entre sí.

4. No hay un recurso físicamente único que lo justifique.
   Un Singleton se justifica cuando existe un recurso realmente único en el
   proceso (por ejemplo, una única conexión a un archivo de log, o un único
   controlador de hardware). En mi caso, los "espacios de parqueo" son datos
   de negocio, no un recurso técnico único — no hay ninguna razón física para
   que solo pueda existir "una" instancia del repositorio en memoria.

Conclusión: en este caso, el Singleton sería decorativo — resolvería un
problema que ya resolví mejor con Repository + inyección de dependencias.
Por eso esta carpeta no contiene una copia de la base con Singleton
implementado, sino esta explicación.
