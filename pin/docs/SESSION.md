# Manejo de Sesiones en Pin Zero

Pin Zero inicializa la sesión de PHP automáticamente en el `index.php`. Para evitar interactuar directamente con `$_SESSION`, el framework provee funciones de conveniencia en `libs/session.php`.

## Funciones Básicas

- `session_set(string $key, mixed $value)`: Guarda un valor en la sesión.
- `session_get(string $key, mixed $default = null)`: Recupera un valor.
- `session_has(string $key)`: Verifica si existe una llave.
- `session_delete(string $key)`: Elimina una llave específica.
- `session_destroy()`: Cierra la sesión y limpia los datos.

## Mensajes Flash

El sistema utiliza la llave `flash` para mostrar mensajes de éxito o error en la siguiente petición. El enrutador (`load.php`) hace uso de esto para redirecciones de seguridad.

```php
// En tu acción (Controller)
if ($error) {
    session_set('flash', 'Los datos son incorrectos.');
    redirect_to('/login');
}
```

## Seguridad de Sesión

Pin Zero utiliza un sistema de **Rate Limiting** basado en sesiones (`_pin_last_req`) definido en `RATE_LIMIT_MS` (en `settings.php`). Si un usuario hace peticiones demasiado rápido, el sistema lanzará una excepción `429 Too Many Requests`.

## Autenticación

El sistema de ACL integrado en `libs/acl.php` depende de:
1. `session_get('is_logged_in')`: Un booleano que indica si el usuario pasó por el login.
2. `session_get('user_id')`: El ID del usuario para verificar permisos en la base de datos.

### Ejemplo de Login

```php
public function signin() {
    csrf_protect(); // Validación de token CSRF
    // ... validación de clave ...
    session_set('is_logged_in', true);
    session_set('user_id', $user['id']);
    redirect_to('/dashboard');
}
```