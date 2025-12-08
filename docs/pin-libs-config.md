# Pin\Libs\Config

Clase estática que entrega la configuración de base de datos.

## Espacio de nombres
`Pin\Libs\Config`

## API
- `static getDbConfig(): array`  
  Retorna arreglo con `dsn`, `user`, `password` y `parameters` (atributos PDO).

## Notas
- Los valores del DSN usan marcadores `DB_HOST`, `DB_NAME`, `USER_NAME`, `USER_PASSWORD`; ajústalos a tu entorno.
