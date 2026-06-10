# Uso de Base de Datos en Pin Zero

Pin Zero utiliza **SQLite3** como motor de base de datos predeterminado por su simplicidad y "Cero Dependencias". La configuración se encuentra en `pin/config/settings.php`.

## Funciones Globales (`libs/db.php`)

El sistema expone funciones planas para interactuar con la base de datos de forma segura mediante PDO.

### Consultas de Selección

- `db_find_all(string $sql, array $params = [])`: Retorna un array con todos los registros encontrados.
- `db_find_first(string $sql, array $params = [])`: Retorna un solo registro (el primero) o `null`.
- `db_get_scalar(string $sql, array $params = [])`: Retorna un valor único (ej: el resultado de un `COUNT(*)`).

### Ejemplo de Uso

```php
// Buscar todos los usuarios activos
$users = db_find_all("SELECT * FROM users WHERE status = :status", ['status' => 'active']);

// Obtener el nombre de un usuario por ID
$name = db_get_scalar("SELECT name FROM users WHERE id = :id", ['id' => 1]);
```

### Operaciones de Escritura

- `db_execute(string $sql, array $params = [])`: Ejecuta una sentencia SQL (INSERT, UPDATE, DELETE).
- `db_insert(string $table, array $data)`: Inserta un registro a partir de un array asociativo.
- `db_last_insert_id()`: Retorna el último ID generado.

```php
// Inserción simple
db_insert('logs', [
    'event' => 'login_success',
    'created_at' => date('Y-m-d H:i:s')
]);
```

## Seguridad

**Pin Zero** obliga al uso de parámetros preparados (Prepared Statements). Nunca concatenes variables directamente en el SQL:

❌ **Mal:**
`db_find_all("SELECT * FROM users WHERE name = '$name'")`

✅ **Bien:**
`db_find_all("SELECT * FROM users WHERE name = :name", ['name' => $name])`

## Integración con Form Helpers

Puedes usar `options_for_dbselect` para llenar select tags directamente desde la base de datos:

```php
<?php
$roles = db_find_all("SELECT id, role_name FROM roles");
echo select_tag('role_id', options_for_dbselect($roles, 'role_name', 'id'));
?>
```