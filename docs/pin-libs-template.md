# Pin\Libs\Template

Renderiza vistas dentro de un template principal y comparte variables con ellas.

## Espacio de nombres
`Pin\Libs\Template`

## Propiedades claves
- `_template` (default: `default`) — nombre del template en `app/views/_shared/templates/{name}.phtml`.
- `_properties` — variables expuestas a la vista.
- `_title` — disponible en el template como `$title`.

## API
- `set(string $key, $value): void` — Asigna variable para la vista.
- `get(string $key)` — Recupera variable asignada.
- `setTemplate(string $template): void` — Cambia archivo de template.
- `setTitle(string $title): void` — Ajusta título.
- `render(string $view): void` — Renderiza `app/views/{view}.phtml` dentro del template activo.

## Flujo de render
1) Construye ruta del template (`APP_PATH/views/_shared/templates/{template}.phtml`).
2) Hace `ob_start()`, carga la vista vía `Load::view($view, $properties)` y guarda el buffer en `$yield`.
3) Incluye el template, donde están disponibles `$title` y `$yield` (además de las variables de la vista).

## Errores
- Lanza excepción si el archivo de template no existe.
