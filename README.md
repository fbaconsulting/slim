## ROADMAP

### Framework

* Slim Framework se configura mediante un array que tiene una clave llamada settings.
* Estas propiedades se agregan a un objecto `Container`, de Slim Framework.
* Agregamos nuestro propio decorador para agregar validación y segmentar las propiedades:
  * El resto de propiedades se agregan pero fuera de settings.
  * Consideramos como configuración a las propiedades y settings a un elemento dentro de las propiedades.
  * Tipamos la información. Deberíamos disponer de un objeto a modo de DTO llamado `Settings`.
* Solo se pueden agregar settings permitidas. Las dependencias deben agregarse como `Libraries`: `Library`.
* `Library` y `LibraryBuilder`: `DatabaseLibrary` implements `LibraryInterface`.
  * Obliga a devolver el identicador de la librería (para configurar las propiedades de Slim Framework).
* Agregamos nuestro propio decorator a los métodos Http:
  * Request Decorator as `HttpRequest`
    * Agregamos la opción de filtrar los parámetros del request
  * Response Decorator as `HttpResponse`
    * Métodos para identificar cada response como único (hash)

### Slim
* Agregamos algunas librerías de ejemplo, como `DatabaseEloquentLibrary` implements `LibraryInterface`.
* Agregamos librería de logs
* Cargamos las rutas en routes
* Agregamos funciones:
  * url
  * app
  * route
  * view
    * View debe ser configurable, slim-view o blade 
* Agregamos la opción de generar comandos: `CommandInterface`