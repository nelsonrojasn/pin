# Pin Zero

Pin Zero es un mini-framework PHP diseñado para proyectos simples. No usa clases obligatorias ni dependencias externas, solo funciones planas y una estructura clara de páginas, vistas y helpers.

## Requisitos

- PHP 8.0 o superior
- SQLite disponible en PHP

## Qué es Pin

Pin Zero usa un único archivo de entrada: `index.php`. Todas las peticiones llegan allí y se traducen en una **página** y una **acción**.

- La **página** es un archivo PHP en `pin/pages`.
- La **acción** es una función dentro de ese archivo.
- Los parámetros adicionales en la URL se pasan como argumentos a la función.

Esto permite escribir controladores simples sin estructuras complejas.

## Cómo funciona

La función `route()` se encarga de:

1. tomar la URL
2. extraer la página, la acción y los parámetros
3. cargar el archivo de página correspondiente
4. ejecutar `page_initializer()` si existe
5. llamar a la función de acción solicitada

Ejemplo de URL útil:

- `/page/show/contacto` → carga `pin/pages/page.php` y llama a `show('contacto')`

En `load.php` también están las funciones principales:

- `load_view($view, $params = null)` - carga una vista y pasa variables locales
- `load_partial($partial, $params = null)` - carga un parcial dentro de otra vista
- `load_helper($helper)` - carga un helper desde `pin/helpers`
- `redirect_to($url)` - redirige a otra ruta

## Estructura del proyecto

- `pin/pages` - archivos de páginas con funciones de acción
- `pin/views` - vistas HTML/PHP
- `pin/partials` - fragmentos reutilizables de vista
- `pin/helpers` - helpers para HTML, formularios y CSRF
- `pin/libs` - funciones de soporte para DB, sesión y request
- `db` - base de datos SQLite y datos persistentes

## Manejo de sesión y autenticación

La autenticación se maneja con funciones de sesión simples:

- `session_set($key, $value)`
- `session_get($key)`
- `session_delete($key)`
- `session_destroy()`

El login de demostración está en `/login` y el formulario se envía a `/login/signin`.

Credenciales de ejemplo:

- Usuario: `admin`
- Clave: `pin`

En `pin/pages/login.php` se usa `csrf_protect()` para validar el token CSRF antes de procesar el formulario.

## CSRF y request seguros

Pin Zero ofrece helpers para proteger formularios:

- `csrf_field_tag()` - agrega el campo oculto con el token
- `csrf_meta_tag()` - expone el token para JavaScript
- `csrf_check()` - compara el token enviado con el almacenado en sesión
- `csrf_protect()` - bloquea POST válidos si faltan los tokens

El helper `request.php` maneja la entrada segura desde `$_POST` y `$_GET`:

- `request_post($key, $type = 'string')`
- `request_get($key, $type = 'string')`
- `request_input($key, $type = 'string')`

## Base de datos

Pin Zero usa SQLite a través de funciones planas en `pin/libs/db.php`.

Funciones principales:

- `db_connection()`
- `db_find_all($sql, $params = [])`
- `db_insert($table, $data)`
- `db_update($table, $data, $condition, $params = [])`
- `db_delete($table, $condition, $params = [])`
- `db_exec($sql, $params = [])`
- `db_begin_transaction()`

La base de datos se configura en `bootstrap.php` con `DB_PATH` y el archivo SQLite en `db/app.sqlite`.

## HTML y formularios

El helper `pin/helpers/html_tags.php` incluye funciones para generar etiquetas y campos de formulario con escape seguro:

- `link_to()`
- `form_tag()` / `end_form_tag()`
- `text_field_tag()`
- `password_field_tag()`
- `text_area_tag()`
- `hidden_field_tag()`
- `submit_tag()`
- `label_tag()`
- `select_tag()`

Estas funciones evitan la repetición y ayudan a mantener el HTML limpio.

## Cómo crear una nueva página

1. Crear `pin/pages/nombre.php` con funciones para cada acción.
2. Crear las vistas correspondientes en `pin/views/nombre/`.
3. Usar `load_view()` para renderizar vistas con datos.
4. Proteger rutas con `session_get('is_logged_in')` o `page_initializer()`.

## Filosofía

Pin Zero busca claridad y simplicidad:

- menos abstracción, más funciones directas
- menos dependencias externas
- stdlib PHP y estructuras sencillas
- diseño comprensible para quien empieza o quiere un proyecto pequeño

