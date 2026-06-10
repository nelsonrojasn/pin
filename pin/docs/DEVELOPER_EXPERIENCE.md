# Experiencia del Desarrollador (DX)

**Pin Zero** está diseñado para ser "Invisible". Aquí te explicamos cómo trabajar con el flujo del framework.

## Creando una nueva Página

1. Crea un archivo en `pin/pages/` (Pública) o `pin/pages/private/` (Privada).
2. El nombre del archivo debe ser `mi_pagina.php` (snake_case).
3. La clase dentro debe ser `MiPaginaPage` (PascalCase + Sufijo Page).
4. Cada método público es una **Acción**.

```php
// pin/pages/contact.php
class ContactPage {
    public function index() {
        load_view('contact/form');
    }
    
    public function send() {
        // Lógica de envío
    }
}
```

## El Sistema de URLs Ofuscadas

A diferencia de otros frameworks, Pin Zero no expone las rutas en la URL. 

- **Visible en el navegador:** `index.php?r=AQD234... (Hash AES-256)`
- **Interno:** `page=contact&action=index`

Para generar enlaces, usa siempre el helper `url()`:

```php
<a href="<?php echo url('contact', 'index', ['ref' => 'web']); ?>">Contacto</a>
```

## Vistas y Parámetros

`load_view(vista, params)` extrae el array de parámetros para que estén disponibles como variables locales en el archivo `.phtml`.

```php
// En el Page
load_view('user/profile', ['name' => 'Nelson', 'role' => 'Admin']);

// En pin/views/user/profile.phtml
<p>Nombre: <?php echo html($name); ?></p>
```

## Inicializadores

Si tu clase Page tiene un método llamado `page_initializer()`, el enrutador lo ejecutará automáticamente antes de la acción solicitada. Es ideal para cargar modelos o verificar estados comunes a toda la página.

## Autoloading

Cualquier clase en `pin/libs/` que siga la convención de nombres será cargada automáticamente. Por ejemplo, `class UserHelper` debería estar en `pin/libs/user_helper.php`.