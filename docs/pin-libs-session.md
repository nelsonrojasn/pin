# Pin\Libs\Session

Atajos estáticos para manipular `$_SESSION`.

## Espacio de nombres
`Pin\Libs\Session`

## API
- `static get($key): mixed|null`
- `static set($key, $value): void`
- `static delete($key): void`
- `static destroy(): void` — Cierra la sesión actual.

## Notas
- Asume que la sesión ya está iniciada (`session_start()`).
- No implementa namespaces ni expiración; gestiona llaves simples.
