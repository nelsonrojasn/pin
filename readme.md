# Pin Zero

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/build-status/main)


Si te gusta PHP pero no quieres la complejidad de un marco pesado, te presento a **Pin Zero**. **Pin Zero** es una herramienta pequeña pensada para proyectos sencillos que no necesitan muchas funciones.

## Filosofía

**Pin Zero** es un micro-framework de "Cero Dependencias" diseñado para aplicaciones donde la seguridad por ofuscación y el minimalismo son prioridades.

### Características Principales

- **Controlador Frontal Centralizado**: Todas las peticiones pasan por `index.php` y son gestionadas por `load.php`.
- **Arquitectura Orientada a Objetos**: Las páginas ahora son clases (ej. `LoginPage`, `SetupPage`) donde cada método público es una acción ejecutable.
- **Blindaje Criptográfico (URL Shield)**: Las rutas y parámetros no se exponen en texto plano. Se utiliza cifrado AES-256-CBC con firmas HMAC para evitar cualquier manipulación o descubrimiento de rutas (Anti-Fuzzing).
- **ACL Empresarial**: Sistema de permisos multi-perfil, multi-recurso y soporte nativo para Multi-tenancy (Empresas/Inquilinos).
- **Zero Dependencies**: Funciona exclusivamente con PHP moderno y SQLite, sin necesidad de Composer.

## Requisitos

- PHP 8.0 o superior
- Extensión `openssl` y `pdo_sqlite` habilitadas.

## Ubicación de sus componentes

- Las páginas están ubicadas en `pin/pages`.
- Las vistas están en `pin/views` (formato `.phtml`).
- Puedes incluir ayudantes en la carpeta `pin/helpers`.
- Las utilidades y funciones planas están en `pin/libs`.
- Los parciales deben crearse en la carpeta `pin/partials`.
- Las páginas que requieran autenticación basta con crearlas en `pin/pages/private`.

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

La función `route()` busca primero la página en la sección pública, y si no la encuentra la busca en la sección privada automáticamente, aplicando entonces el filtro de autenticación. Con esto tampoco es necesario cambiar las url para que apunten a la carpeta privada.

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

Ver ejemplos detallados, en `pin/docs/DB_USAGE.md`.

Por cierto, **Pin Zero** funciona sin dependencias externas y está pensado para PHP moderno.

¡Saludos!
