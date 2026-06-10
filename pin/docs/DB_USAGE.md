# Uso de Base de Datos en Pin Zero

Pin Zero utiliza **SQLite3** como motor de base de datos predeterminado por su simplicidad y "Cero Dependencias". La configuración se encuentra en `pin/config/settings.php`.

## Funciones Globales (`libs/db.php`)

El sistema expone funciones planas para interactuar con la base de datos de forma segura mediante PDO.

### Consultas de Selección

- `db_find_all(string $sql, array $params = [])`: Retorna un array con todos los registros encontrados.
- `db_find_first(string $sql, array $params = [])`: Retorna un solo registro (el primero) o `null`.
- `db_get_scalar(string $sql, array $params = [])`: Retorna un valor único (ej: el resultado de un `COUNT(*)`). La primera columna de la primera fila será devuelta.


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
/* 
INSERT INTO logs (event, created_at) VALUES (:event, :created_at) 
*/
```

- `db_update(string $table, array $data, string $condition, ?array $params = null)`: Actualiza un registro a partir de un array asociativo.
- `db_delete(string $table, string $condition, ?array $params = null)`: Elimina un registro.

```php  
$rows_deleted = db_delete('users', 'id = ?', [1]);
/* 
DELETE FROM users WHERE id = ? 
*/

$data = ['status' => 'inactive'];
$rows_updated = db_update('users', $data, 'id = ?', [1]);
/* 
UPDATE users SET status = :status WHERE id = ? 
*/
```

### Transacciones
- `db_begin_transaction()`: Inicia una transacción.
- `db_commit()`: Confirma la transacción.
- `db_rollback()`: Deshace la transacción.

En caso de crear múltiples registros, existirá un mayor rendimiento si se usa una transacción en vez de instrucciones atómicas.


## Seguridad

**Pin Zero** obliga al uso de parámetros preparados (Prepared Statements). Nunca concatenes variables directamente en el SQL:

❌ **Mal:**
`db_find_all("SELECT * FROM users WHERE name = '$name'")`

✅ **Bien:**
`db_find_all("SELECT * FROM users WHERE name = :name", ['name' => $name])`

## Integración con la Vista

Puedes usar `options_for_dbselect` para llenar select tags directamente desde la base de datos:

```php
<?php $roles = db_find_all("SELECT id, role_name FROM roles"); ?>
<select name="role_id">
    <?php echo options_for_dbselect($roles, 'role_name', 'id'); ?>
</select>
```