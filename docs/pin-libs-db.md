# Pin\Libs\Db

Wrapper estático sobre PDO para operaciones comunes y transacciones.

## Espacio de nombres
`Pin\Libs\Db`

## Conexión
- Usa `Config::getDbConfig()` para construir la conexión PDO (persistente, `ERRMODE_EXCEPTION`).

## Lectura
- `static findAll(string $sql, array $params = null): array` — Ejecuta consulta y retorna todas las filas (assoc).
- `static findFirst(string $sql, array $params = null): ?array` — Primera fila o `null`.
- `static getScalar(string $sql)` — Primer valor de la primera fila o `null`.

## Escritura
- `static insert(string $table, array $data): string` — Inserta y retorna `lastInsertId()`.
- `static update(string $table, array $data, string $condition = null): int` — Filas afectadas.
- `static delete(string $table, string $condition, array $params = null): int` — Filas afectadas.

## Transacciones
- `static beginTransaction()`  
- `static commit()`  
- `static rollback()`  

## Internos relevantes
- `connect()` abre conexión bajo demanda (singleton).
- `_exec()` prepara/ejecuta statements parametrizados y es base para el resto.
