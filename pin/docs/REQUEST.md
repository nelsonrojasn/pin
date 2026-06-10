# Manejo de Peticiones y Seguridad

El archivo `pin/libs/request.php` centraliza la captura de datos de entrada (`$_GET`, `$_POST`), aplicando limpieza y tipado básico para prevenir inyecciones.

## Captura de Datos

Se recomienda no usar las superglobales directamente. Usa las siguientes funciones:

- `request_post(key, type)`: Captura desde POST.
- `request_get(key, type)`: Captura desde GET.
- `request_input(key, type)`: Busca en POST, y si no existe, en GET.

### Tipos de Sanitización

El segundo parámetro `type` puede ser:
- `string` (por defecto): Limpia espacios y elimina bytes nulos.
- `int` / `integer`: Cast a entero.
- `bool` / `boolean`: Valida booleanos (filtros de PHP).
- `email`: Valida formato de correo o retorna `null`.
- `url`: Valida formato de URL o retorna `null`.

```php
$email = request_post('email', 'email');
$id = request_get('id', 'int');
```

## Salida Segura (XSS)

Para mostrar datos en las vistas (`.phtml`), usa siempre la función `html()` para escapar caracteres especiales:

```php
<!-- En tu vista -->
<h1>Hola, <?php echo html($user_name); ?></h1>
```

## Protección CSRF

Pin Zero protege tus formularios de ataques de falsificación de petición en sitios cruzados.

1. **En el formulario:**
   ```php
   <form method="POST" action="...">
       <?php echo csrf_field_tag(); ?>
       <!-- ... -->
   </form>
   ```
2. **En la acción del Page:**
   ```php
   csrf_protect(); // Lanza una excepción 403 si el token es inválido
   ```