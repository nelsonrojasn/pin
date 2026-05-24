# Pin

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/build-status/main)


Si amas PHP, pero no la complejidad de un marco de trabajo masivo, entonces me gustaría presentarte a **Pin**. **Pin** es una herramienta que he creado para simplificarme a mí mismo y mis futuros trabajos que no requieren de funcionalidades complejas.


Intentaré explicarlo en unos pocos pasos.

**Pin** utiliza el patrón Front Controller, lo que significa que cada solicitud será capturada por el archivo index.php. El Front Controller traduce la solicitud en una **página** y una **acción**. La **página** es solo un archivo php, no una clase, solo un conjunto de funciones. La **acción** corresponde a una función dentro de este archivo.

Es una simplificación de MVC, pero usando solo funciones. He separado la lógica de negocio (o simplemente lógica) en cada función de las **páginas**. Puedes crear clases para encapsular la lógica fuera de las páginas. Depende de ti.

Luego, el resultado de aplicar la lógica se pasa a la vista.


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

Por cierto, **Pin** funciona sin dependencias externas y está pensado para PHP moderno.

¡Saludos!
