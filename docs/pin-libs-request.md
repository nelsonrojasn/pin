# App\Libs\Request

Helpers estáticos para leer parámetros `$_GET` y `$_POST` con `filter_has_var`.

## Espacio de nombres
`App\Libs\Request` *(el archivo vive en `pin/libs/Request.php`; considera moverlo a `app/libs` o ajustar el namespace si buscas consistencia).*

## API
- `static post(string $key): mixed|null`
- `static hasPost(string $key): bool`
- `static get(string $key): mixed|null`
- `static hasGet(string $key): bool`

## Notas
- No sanitiza ni valida; complementa con `htmlspecialchars`, validación de dominio, etc.
