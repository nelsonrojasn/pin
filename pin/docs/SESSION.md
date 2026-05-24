# Session Management Functions

## Overview

Simple functions to manage user sessions for authentication and data persistence.

## API

### `session_get($key)`

Get a session value by key.

```php
$user_id = session_get('user_id');
$username = session_get('username'); // Returns null if not set
```

### `session_set($key, $value)`

Set a session value.

```php
session_set('user_id', 123);
session_set('username', 'john_doe');
session_set('is_admin', true);
```

### `session_has($key)`

Check if a session key exists.

```php
if (session_has('user_id')) {
    echo "User is logged in";
}
```

### `session_delete($key)`

Delete a session value.

```php
session_delete('user_id');
session_delete('username');
```

### `session_destroy()`

Destroy the entire session.

```php
session_destroy();  // Logs user out completely
```

## Common Patterns

### Authentication Check

```php
if (session_get('is_logged_in') !== true) {
    session_set('flash', 'You must log in first');
    redirect_to('login');
}
```

### Flash Messages

```php
// Set message
session_set('flash', 'User updated successfully');

// Display in template
<?php if (session_has('flash')): ?>
    <div class="alert"><?= html(session_get('flash')) ?></div>
    <?php session_delete('flash'); ?>
<?php endif; ?>
```

### Login

En este proyecto, la ruta de login es `login` y el formulario se envía a `login/signin`.

Credenciales de demostración:

- Usuario: `admin`
- Clave: `pin`

Recuerda proteger los endpoints POST con `csrf_protect()`.

```php
function signin() {
    csrf_protect();
    session_set('is_logged_in', true);
    session_set('user_id', 42);
    session_set('username', 'john');
    session_set('flash', 'Welcome back!');
    redirect_to('');
}
```

### Logout

```php
function logout() {
    session_destroy();
    session_set('flash', 'Logged out');
    redirect_to('login');
}
```

### Store User Data

```php
// After login validation
session_set('user', [
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'role' => $user['role']
]);

// Access later
$user = session_get('user');
echo $user['name'];
```
