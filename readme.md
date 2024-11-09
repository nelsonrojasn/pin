# Pin

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/build-status/main)


Si amas PHP, pero no la complejidad de un marco de trabajo masivo, entonces me gustaría presentarte a **Pin**. **Pin** es una herramienta que he creado para simplificarme a mí mismo y mis futuros trabajos que no requieren de funcionalidades complejas.


Intentaré explicarlo en unos pocos pasos.

**Pin** utiliza el patrón Front Controller, lo que significa que cada solicitud será capturada por el archivo index.php. El Front Controller traduce la solicitud en una **página** y una **acción**. La **página** es solo un archivo php, no una clase, solo un conjunto de funciones. La **acción** corresponde a una función dentro de este archivo.

Es una simplificación de MVC, pero usando solo funciones. He separado la lógica de negocio (o simplemente lógica) en cada función de las **páginas**. Puedes crear clases para encapsular la lógica fuera de las páginas. Depende de ti.

Luego, el resultado de aplicar la lógica se pasa a la vista.


## Ubicación de sus componentes

- Las páginas están ubicadas en pin/pages.
- Las vistas deben colocarse en pin/views.
- Puedes incluir ayudantes en la carpeta pin/helpers.
- Si necesitas crear clases para manejar la lógica, por favor colócalas dentro de la carpeta pin/libs.
- Los parciales deben crearse en la carpeta pin/partials.
- Para hacer renderizado de vistas dentro de una plantilla se ha implementado una clase llamada Template, puedes encontrarla en pin/libs.


## Clases de utilidad

Hay una clase de base de datos incluida dentro de pin/libs (la **clase Db**) que te permite consultar y modificar datos en una base de datos.

Para manejar variables de sesión, puedes usar la **clase Session** que se encuentra en la misma carpeta que la clase Db.

Finalmente, hay una clase para manejar elementos de solicitudes post y get: la **clase Request** (en la misma carpeta anterior).

La clase global **Load** te permite "incluir" diferentes contenidos dentro de las páginas o incluso en clases o vistas.

Por último, pero no menos importante, hay un conjunto de funciones que apoyan escribir menos código para cargar css, js, dibujar formularios y otros elementos html útiles.

Es posible ver ejemplos de páginas dentro de la carpeta pin/pages para tener una idea de cómo funciona.

Puedes dirigirte a la documentación en la carpeta pin/docs para tener una idea de cómo funciona cada cosa y algunos ejemplos específicos.

Por cierto, **Pin** requiere como mínimo PHP 5.6.

¡Saludos!
