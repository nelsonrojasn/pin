# Pin\Libs\QueryBuilder

Constructor encadenable de consultas SQL SELECT.

## Espacio de nombres
`Pin\Libs\QueryBuilder`

## Uso básico
```php
$qb = new QueryBuilder('products');
$qb->columns('id, name')
   ->where('active = 1')
   ->orderBy('name ASC')
   ->limit(10);

$sql = (string) $qb; // genera la consulta
```

## API encadenable
- `__construct(string $table)` — Define la tabla.
- `columns(string $cols)`
- `where(string $condition)` — Usa operador `AND` entre condiciones sucesivas.
- `orWhere(string $condition)` — Usa operador `OR`.
- `join(string $join)` — Adjunta texto de join.
- `group(string $group)`
- `having(string $having)`
- `orderBy(string $order)`
- `limit(string|int $limit)`
- `__toString(): string` — Retorna la consulta final.

## Detalles
- No escapa ni valida valores; combínalo con consultas parametrizadas de `Db`.
- El destructor limpia el estado interno.
