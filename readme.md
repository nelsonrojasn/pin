# Pin Zero

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/build-status/main)


Si te gusta PHP pero no quieres la complejidad de un marco pesado, te presento a **Pin Zero**. **Pin Zero** es una herramienta pequeña pensada para proyectos sencillos que no necesitan muchas funciones.

Lo explico en pocas líneas.

**Pin Zero** usa una sola entrada para todas las peticiones. El archivo `index.php` recibe cada solicitud y decide qué página y qué acción ejecutar. Esta forma de trabajar se llama controlador frontal. Una **página** es un archivo PHP que contiene funciones, no una clase. Una **acción** es una función dentro de ese archivo.

Es una versión básica de modelo-vista-controlador que usa solo funciones. La lógica de cada página se mantiene en su propio archivo; si quieres, puedes sacar parte de esa lógica a clases separadas.

Después de ejecutar la lógica, el resultado se muestra en una vista.

## Requisitos

- PHP 8.0 o superior

## Ubicación de sus componentes

- Las páginas están ubicadas en `pin/pages`.
- Las vistas deben colocarse en `pin/views`.
- Puedes incluir ayudantes en la carpeta `pin/helpers`.
- Las utilidades y funciones planas están en `pin/libs`.
- Los parciales deben crearse en la carpeta `pin/partials`.

## Utilidades principales

Pin Zero evita clases innecesarias en el núcleo. Las funciones principales son:

- `route()` - enrutador ligero de Front Controller a página/acción.
- `load_view()` - carga vistas con parámetros.
- `redirect_to()` - redirección HTTP simple.
- `db_*()` - acceso a base de datos SQLite con funciones planas.
- `session_*()` - manejo simple de sesión.
- `request_*()` - entrada segura desde `$_POST` y `$_GET`.
- `html()` - escape seguro para output HTML.
- `csrf_field_tag()` / `csrf_meta_tag()` - CSRF seguro para formularios y JS.

El login de demostración está en `/login`. Usa estas credenciales de prueba:

- Usuario: `admin`
- Clave: `pin`

El formulario de login usa CSRF y el control se valida con `csrf_protect()` en la acción `login/signin`.

No necesitas entender clases estáticas para usar el núcleo.

Puedes dirigirte a la documentación en la carpeta `pin/docs` para ejemplos específicos.

### Documentación recomendada

- `pin/docs/DB_USAGE.md`
- `pin/docs/SESSION.md`
- `pin/docs/REQUEST.md`
- `pin/docs/DEVELOPER_EXPERIENCE.md`

Ver ejemplos detallados, incluido el uso de `QueryBuilder`, en `pin/docs/DB_USAGE.md`.

Por cierto, **Pin Zero** funciona sin dependencias externas y está pensado para PHP moderno.

¡Saludos!
