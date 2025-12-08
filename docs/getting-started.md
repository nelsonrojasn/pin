# Pin v2.0 – Getting Started

Bienvenido a **Pin v2.0**. Este documento introduce la arquitectura, filosofía y los conceptos fundamentales del framework, enfocado en **simplicidad**, **claridad** y **mantenibilidad** por encima del dinamismo complejo.

---

## 🧩 Fundamentos de la Web

Pin se construye sobre tres principios básicos:

1. El cliente realiza una petición.  
2. El servidor procesa la solicitud.  
3. El servidor responde al cliente.

No hay magia. No hay exceso de abstracción. Sólo la web como siempre ha funcionado.

---

## 🗂️ Estructura de Pin

Pin organiza su arquitectura en componentes simples y explícitos.

### **1. Front Controller**
El archivo `index.php` recibe todas las peticiones y las canaliza al Router.

### **2. Rutas**
El archivo `routes.php` contiene rutas estáticas y sus handlers asociados.

### **3. Router**
Compara la solicitud entrante con la lista definida y despacha el handler adecuado.

### **4. Handler**
Abarca la lógica mínima para atender una petición específica.  
Debe implementar:

```php
public function handle(array $params);
```

---

## 📦 Librerías Incluidas

Pin incluye utilidades esenciales para simplificar tareas comunes:

### **Db**
Gestiona la conexión y las operaciones con la base de datos.

### **Config**
Almacena la configuración del proyecto, especialmente los parámetros de conexión a BD.

### **Request**
Proporciona métodos estáticos para leer parámetros enviados vía **GET** o **POST**.

### **Session**
Manipula valores de sesión (lectura/escritura).

### **Template**
Se encarga de procesar vistas, plantillas y generar contenido final.

### **QueryBuilder**
Permite construir consultas SQL mediante un patrón *builder* encadenable.

---

# 🛠️ Handlers

Un **handler** es responsable de atender **una única petición** proveniente del navegador.

Su misión es:

1. Recibir la petición.  
2. Elegir el modelo que ejecutará la lógica correspondiente.  
3. Enviar la respuesta o redirigir cuando sea necesario.

Esto sigue el principio:

> **Slim handler, fat model**  
> (Handlers delgados, modelos robustos)

---

# 🧠 Modelos

Los modelos se encargan de la **lógica de negocio**:

- Cálculos.  
- Consultas a la base de datos.  
- Inserción, actualización o eliminación de datos.  
- Escritura de logs.  
- Envío de correos.  
- Manejo de archivos adjuntos.  
- Ejecución de tareas internas.

---

# 🎨 Vistas, Templates y Partials

Las vistas representan la **respuesta enviada al cliente**.

## **Templates**
Un template envuelve una vista para aplicar estructura uniforme, como:

- Encabezado  
- Pie de página  
- Estilos comunes  

También permite generar formatos como:

- PDF  
- CSV  
- XML  
- JSON  

## **Partials**
Son fragmentos reutilizables, tales como:

- Barra de navegación  
- Formularios  
- Pie de página  
- Menús o elementos compartidos  

---

# 🚀 Conclusión

Pin v2.0 busca devolver la claridad al desarrollo web eliminando capas innecesarias y manteniendo el control total en el desarrollador.  
Una arquitectura sencilla que potencia la mantenibilidad y la comprensión inmediata del flujo.
