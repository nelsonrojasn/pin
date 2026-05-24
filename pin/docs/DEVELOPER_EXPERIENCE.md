# Pin Zero - Developer Experience Checklist

Este documento describe los puntos clave para medir y mejorar la experiencia de desarrollo de Pin Zero.

## Objetivo

Hacer que Pin Zero sea fácil de entender, fácil de configurar y fácil de modificar para un desarrollador autodidacta.

## Checklist

### 1. Primer contacto
- [ ] Clonar el repositorio es suficiente para comenzar.
- [ ] Instrucciones claras de instalación y ejecución están disponibles en `readme.md`.
- [ ] No hay dependencias externas complejas.
- [ ] El proyecto funciona con PHP nativo + SQLite sin necesidad de instalar paquetes adicionales.

### 2. Estructura de archivos
- [ ] Las páginas están en `pin/pages`.
- [ ] Las vistas están en `pin/views`.
- [ ] Las funciones utilitarias están en `pin/libs`.
- [ ] Las vistas parciales están en `pin/partials`.
- [ ] La configuración principal está en `bootstrap.php`.

### 3. Código y conceptos básicos
- [ ] Las funciones principales son fáciles de entender: `route()`, `load_view()`, `redirect_to()`.
- [ ] La base de datos usa funciones planas: `db_*()`.
- [ ] La sesión usa funciones planas: `session_*()`.
- [ ] La solicitud usa funciones planas: `request_*()`.
- [ ] No se requiere leer o entender clases estáticas para usar el núcleo.

### 4. Desarrollo rápido
- [ ] Crear una nueva ruta debería requerir solo un archivo en `pin/pages` y un archivo de vista.
- [ ] Añadir datos desde un formulario debe poder hacerse en pocas líneas con `request_post()`.
- [ ] El manejo de sesiones debe ser obvio con `session_set()`, `session_get()` y `session_delete()`.
- [ ] El renderizado de vistas debe ser simple con `load_view("page/name", [..])`.

### 5. Seguridad y buenas prácticas
- [ ] Las entradas del usuario se sanitizan automáticamente en `request_*()`.
- [ ] El output HTML en plantillas se escapa con `html()`.
- [ ] La base de datos usa consultas preparadas con `db_exec()`.
- [ ] El archivo `load.php` no oculta lógica compleja: es corto y fácil de leer.

### 6. Documentación y aprendizaje
- [ ] Existe documentación de uso para la base de datos (`pin/docs/DB_USAGE.md`).
- [ ] Existe documentación para sesiones (`pin/docs/SESSION.md`).
- [ ] Existe documentación para request/sanitización (`pin/docs/REQUEST.md`).
- [ ] Existe esta guía de experiencia de desarrollo.

### 7. Pruebas manuales rápidas
- [ ] Un desarrollador puede desplegar el proyecto en un servidor local en menos de 10 minutos.
- [ ] Puede crear una nueva página de ejemplo sin buscar clases o archivos extraños.
- [ ] Puede leer la documentación y entender el flujo de request → página → vista.

## Recomendaciones

- Mantén los nombres de funciones simples y consistentes.
- Evita clases innecesarias en el núcleo.
- Mantén `bootstrap.php` corto y enfocado en configuración.
- Enfócate en lectura fácil, no en patrones complejos.
- Actualiza esta guía según descubras nuevas necesidades de los usuarios.
