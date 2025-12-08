# Pin\Libs\Load

Facilita carga de archivos (libs, configs, helpers, vistas) y redirecciones.

## Espacio de nombres
`Pin\Libs\Load`

## API
- `static lib(string $lib)` — Carga `pin/libs/{lib}.php`.
- `static config(string $config)` — Carga `pin/config/{config}.php`.
- `static helper(string $helper)` — Carga `pin/helpers/{helper}.php`.
- `static view(string $view, array $params = null)` — Incluye `app/views/{view}.phtml`, extrayendo parámetros como variables.
- `static partial(string $partial, array $params = null)` — Incluye `app/views/_shared/partials/{partial}.phtml`.
- `static redirect(string $url)` — Redirige a `PUBLIC_PATH . $url` con HTTP 301 y `exit`.

## Notas
- Lanza excepción si el archivo solicitado no existe.
- Usa constantes globales `PIN_PATH`, `APP_PATH`, `PUBLIC_PATH` y `DS`.
