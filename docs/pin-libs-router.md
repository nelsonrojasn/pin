# Pin\Libs\Router

Router estático que mapea rutas declaradas a handlers PSR-4 (`App\Handlers`).

## Espacio de nombres
`Pin\Libs\Router`

## Flujo
1) Registrar rutas: `register($method, $path, $handler)` o `registerRoutes(array $routes)`.  
2) Despachar: `dispatch(string $url)` busca coincidencia exacta o con parámetros `{param}` y ejecuta el handler.

## Convenciones
- Las rutas se declaran como `['GET /path' => 'MyHandler']`.
- El handler ejecutable debe existir en `App\Handlers\{Handler}` y extender `App\Handlers\Handler`.
- Parámetros de ruta se envían como array posicional a `handle(array $params)`.

## Errores
- 404 si no hay coincidencia; lanza excepción `Ruta no encontrada`.
- 500 si el handler no existe o no extiende `Handler`.

## Ejemplo
```php
$router = new \Pin\Libs\Router();
$router->registerRoutes(require 'routes.php');
$router->dispatch('/page/show/slug-ejemplo');
```
