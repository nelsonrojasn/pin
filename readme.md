# Pin 2.0

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/nelsonrojasn/pin/badges/build.png?b=main)](https://scrutinizer-ci.com/g/nelsonrojasn/pin/build-status/main)

Si amas PHP, pero no la complejidad de un framework masivo, **Pin 2.0** es para ti. Un micro-framework ligero y moderno que elimina la sobrecarga innecesaria sin sacrificar estructura y claridad.

**Pin 2.0** representa una evolución significativa:
- **Routing estático**: Rutas explícitas y predecibles en un único archivo (`routes.php`).
- **Handlers namespaced**: Cada ruta mapea a una clase `Handler` dedicada con método único `handle()`.
- **PSR-4 Autoloading**: Carga automática de clases con namespaces organizados.
- **Separación clara**: Lógica de aplicación en handlers, utilidades en librerías namespaced.

---

## Iniciando

### Requisitos
- PHP 7.4+
- Servidor web con soporte para URL rewriting (Apache con `mod_rewrite` o similar)

### Instalación

```bash
git clone https://github.com/nelsonrojasn/pin.git
cd pin
php -S localhost:8000
```

Accede a `http://localhost:8000` en tu navegador.

---

## Estructura del Proyecto

```
pin/
├── app/                          # Código nuevo (namespaced)
│   ├── Autoloader.php           # PSR-4 autoloader
│   ├── Router.php               # Enrutador estático
│   ├── handlers/                # Handlers (controllers)
│   │   ├── Handler.php          # Clase base abstracta
│   │   ├── DefaultHandler.php
│   │   ├── PageHandler.php
│   │   └── LoginHandler.php
│   └── libs/                    # Librerías namespaced
│       ├── Session.php
│       ├── Template.php
│       └── Request.php
│
├── pin/                          # Contenido específico del sitio
│   ├── config/
│   │   └── database.php
│   ├── helpers/
│   │   └── html_tags.php        # Funciones helper para HTML
│   ├── libs/                    # Librerías core históricas
│   │   ├── Load.php             # Carga dinámicas de archivos
│   │   ├── Config.php
│   │   ├── Db.php
│   │   └── QueryBuilder.php
│   ├── pages/                   # Pages legacy (deprecadas, migrar a handlers)
│   ├── views/
│   │   ├── default/
│   │   ├── page/
│   │   └── ...
│   ├── partials/                # Fragmentos reutilizables
│   │   ├── navbar.phtml
│   │   ├── flash.phtml
│   │   └── templates/
│   ├── templates/               # Plantillas base
│   │   └── default.phtml
│   └── docs/                    # Documentación del sitio
│
├── css/                         # Hojas de estilos
├── js/                          # JavaScript
├── images/                      # Imágenes
│
├── bootstrap.php                # Configuración inicial
├── index.php                    # Punto de entrada (Front Controller)
├── routes.php                   # Definición de rutas
└── README.md                    # Este archivo
```

---

## Definición de Rutas

Todas las rutas se definen en `routes.php`:

```php
<?php

return [
    // Formato: 'MÉTODO /ruta' => 'NombreHandler'
    'GET /' => 'DefaultHandler',
    'POST /login/signin' => 'LoginHandler',
    'POST /login/signout' => 'LoginHandler',
    
    // Con parámetros dinámicos
    'GET /page/show/{slug}' => 'PageHandler',
    'POST /page/update' => 'PageHandler',
];
```

### Sintaxis de Rutas

- `GET /ruta` — Solicitud GET simple
- `POST /ruta` — Solicitud POST simple
- `GET /ruta/{param}` — Con parámetro dinámico (se pasa al handler)
- `PUT /ruta` — Solicitudes PUT
- `DELETE /ruta` — Solicitudes DELETE

Los parámetros capturados se pasan como array al método `handle()` del handler.

---

## Creando un Handler

Los handlers son clases que heredan de `Handler` e implementan un método único `handle()`:

```php
<?php

namespace App\Handlers;

use App\Libs\Template;
use App\Libs\Session;

class MyNewHandler extends Handler
{
    public function handle(array $params = [])
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Lógica basada en el método HTTP
        if ($method === 'GET') {
            $this->showForm();
        } elseif ($method === 'POST') {
            $this->processForm();
        }
    }
    
    private function showForm()
    {
        $template = new Template();
        $template->set('title', 'Mi Formulario');
        $template->render('myform/show');
    }
    
    private function processForm()
    {
        // Procesar datos
        Session::set('flash', '¡Datos guardados!');
        $this->redirect('/');
    }
    
    private function redirect($path)
    {
        $url = PUBLIC_PATH . $path;
        header("Location: $url", true, 301);
        exit;
    }
}
```

### Registrar la Ruta

En `routes.php`:

```php
'GET /myform' => 'MyNewHandler',
'POST /myform/save' => 'MyNewHandler',
```

---

## Librerías Principales

### Session

Gestiona variables de sesión de forma segura:

```php
use App\Libs\Session;

// Establecer variable
Session::set('user_id', 123);

// Obtener variable
$userId = Session::get('user_id');

// Verificar si existe
if (Session::get('is_logged_in') !== true) {
    // Redirigir a login
}

// Eliminar variable
Session::delete('user_id');

// Destruir sesión
Session::destroy();
```

### Template

Renderiza vistas dentro de una plantilla:

```php
use App\Libs\Template;

$template = new Template();

// Asignar datos
$template->set('title', 'Mi Página');
$template->set('user', $user);

// Cambiar plantilla (si no es la default)
$template->setTemplate('custom');

// Renderizar vista dentro de la plantilla
$template->render('products/index');
```

Las variables asignadas con `set()` están disponibles en las vistas:

```php
<!-- En pin/views/products/index.phtml -->
<h1><?php echo $title; ?></h1>
<p>Bienvenido, <?php echo $user['name']; ?></p>
```

### Request

Accede a datos de solicitudes GET/POST:

```php
use App\Libs\Request;

// Obtener parámetro POST
$email = Request::post('email');

// Verificar si existe parámetro POST
if (Request::hasPost('email')) {
    // Procesar
}

// Obtener parámetro GET
$page = Request::get('page');

// Verificar si existe parámetro GET
if (Request::hasGet('search')) {
    // Buscar
}
```

### Load (Pin\Libs)

Carga dinámicamente archivos:

```php
use Pin\Libs\Load;

// Cargar vista (con parámetros)
Load::view('products/list', ['items' => $items]);

// Cargar parcial
Load::partial('navbar', ['user' => $user]);

// Cargar helper
Load::helper('html_tags');

// Cargar configuración
Load::config('database');

// Redirigir
Load::redirect('products');
```

### Db (Pin\Libs)

Acceso a la base de datos con PDO:

```php
use Pin\Libs\Db;

// Obtener todos los registros
$products = Db::findAll('SELECT * FROM products WHERE active = 1');

// Obtener un registro
$user = Db::findFirst('SELECT * FROM users WHERE id = ?', [123]);

// Obtener un valor escalar
$count = Db::getScalar('SELECT COUNT(*) FROM products');

// Insertar
$lastId = Db::insert('users', ['name' => 'Juan', 'email' => 'juan@example.com']);

// Actualizar
Db::update('users', ['name' => 'Carlos'], 'WHERE id = ?', [1]);

// Eliminar
Db::delete('users', 'WHERE id = ?', [1]);

// Transacciones
Db::beginTransaction();
try {
    Db::insert('orders', [...]);
    Db::insert('items', [...]);
    Db::commit();
} catch (Exception $e) {
    Db::rollback();
}
```

### QueryBuilder (Pin\Libs)

Constructor de consultas SQL:

```php
use Pin\Libs\QueryBuilder;

$qb = new QueryBuilder('products');
$qb->columns('id, name, price')
   ->where('category = :cat')
   ->where('price > :price')
   ->orderBy('name ASC')
   ->limit(10);

$sql = (string) $qb;
// SELECT id, name, price FROM products WHERE category = :cat AND price > :price ORDER BY name ASC LIMIT 10

$results = Db::findAll($sql, [':cat' => 'electronics', ':price' => 100]);
```

---

## Ejemplo Completo: CRUD de Productos

### 1. Definir Rutas

```php
// routes.php
'GET /products' => 'ProductListHandler',
'GET /products/new' => 'ProductFormHandler',
'POST /products' => 'ProductCreateHandler',
'GET /products/{id}' => 'ProductShowHandler',
'GET /products/{id}/edit' => 'ProductEditHandler',
'PUT /products/{id}' => 'ProductUpdateHandler',
'DELETE /products/{id}' => 'ProductDeleteHandler',
```

### 2. Crear Handler

```php
<?php

namespace App\Handlers;

use App\Libs\Template;
use App\Libs\Request;
use Pin\Libs\Db;

class ProductListHandler extends Handler
{
    public function handle(array $params = [])
    {
        // Obtener productos
        $products = Db::findAll('SELECT * FROM products ORDER BY name');
        
        // Preparar vista
        $template = new Template();
        $template->set('products', $products);
        $template->render('products/list');
    }
}
```

### 3. Crear Vista

```html
<!-- pin/views/products/list.phtml -->
<div class="container">
    <h1>Productos</h1>
    <a href="/products/new" class="btn btn-primary">Nuevo Producto</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td>$<?php echo number_format($product['price'], 2); ?></td>
                <td>
                    <a href="/products/<?php echo $product['id']; ?>/edit">Editar</a>
                    <a href="#" onclick="deleteProduct(<?php echo $product['id']; ?>)">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
```

---

## Helpers HTML

En `pin/helpers/html_tags.php` encontrarás funciones útiles:

```php
<?php

// Incluir el helper
\Pin\Libs\Load::helper('html_tags');

// Ahora puedes usar las funciones
echo form_open(['action' => 'save']);
echo form_input('email', 'Email');
echo form_submit('Guardar');
echo form_close();

// Y otras funciones de utilidad
?>
```

---

## Migrando de Pin 1.0 a Pin 2.0

Si tienes código legacy en `pin/pages`, considera migrar a handlers namespaced:

**Antes (Pin 1.0):**
```php
<?php
function show() {
    $template = new Template();
    $template->render("product/show");
}
```

**Después (Pin 2.0):**
```php
<?php
namespace App\Handlers;

use App\Libs\Template;

class ProductHandler extends Handler {
    public function handle(array $params = []) {
        $template = new Template();
        $template->render("product/show");
    }
}
```

---

## Mejores Prácticas

1. **Usa namespaces**: Mantén todo bajo `App\*` o `Pin\Libs`.
2. **Handlers simples**: Un handler = una responsabilidad principal.
3. **Vistas limpias**: Lógica en handlers, presentación en vistas.
4. **Seguridad**: Siempre sanitiza entrada con `htmlspecialchars()`.
5. **Transacciones**: Usa `Db::beginTransaction()` para operaciones críticas.
6. **Errores**: Confía en el manejo de excepciones de `bootstrap.php`.

---

## Recomendaciones

- Lee la documentación en `pin/docs/` para detalles avanzados.
- Estudia los handlers existentes en `app/handlers/`.
- Mantén las vistas (.phtml) simples; lógica compleja va en handlers.
- Usa `Session` para estado del usuario; `Request` para entrada.

---

## Requisitos Mínimos

- **PHP 7.4+** (para sintaxis moderna y type hints)
- **MySQL/MariaDB** (opcional, si usas Db)
- **Servidor web** con URL rewriting

---

## Licencia

Pin 2.0 es open source bajo licencia MIT.

**¡Feliz desarrollo!** 🚀
