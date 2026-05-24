# Request Handling Functions

## Overview

Safe functions to handle `$_POST` and `$_GET` data with automatic sanitization.

All values are sanitized by default to prevent XSS and injection attacks.

## API

### `request_post($key, $type = 'string')`

Get POST value with sanitization.

```php
$name = request_post('name');              // Returns sanitized string or null
$age = request_post('age', 'int');         // Returns integer or null
$email = request_post('email', 'email');   // Returns validated email or null
```

**Type options:**
- `'string'` (default) - HTML escaped
- `'int'` or `'integer'` - Cast to integer
- `'float'` or `'decimal'` - Cast to float
- `'bool'` or `'boolean'` - Boolean value
- `'email'` - Validated email
- `'url'` - Validated URL

### `request_has_post($key)`

Check if POST key exists.

```php
if (request_has_post('email')) {
    // Email was submitted
}
```

### `request_get($key, $type = 'string')`

Get GET value with sanitization.

```php
$search = request_get('q');              // Search query
$page = request_get('page', 'int');      // Page number
$filter = request_get('category');       // Category filter
```

### `request_has_get($key)`

Check if GET key exists.

```php
if (request_has_get('search')) {
    // Search was performed
}
```

### `request_input($key, $type = 'string')`

Get from POST first, then GET (checks POST > GET priority).

```php
$search = request_input('q');  // Works with both $_POST['q'] and $_GET['q']
```

### `html($value)`

Escape value for safe HTML output (use in templates).

```php
<?= html($name) ?>        <!-- Safe from XSS -->
<?= html(session_get('flash')) ?>  <!-- Safe flash message -->
```

### CSRF helpers

The helper library includes CSRF support for form protection.

```php
<form method="POST" action="/login">
    <?= csrf_field_tag() ?>
    <?= text_field_tag('email') ?>
    <?= submit_tag('Login') ?>
</form>
```

Use `csrf_check()` in your handler to validate the submitted token:

```php
if (!csrf_check()) {
    throw new Exception('Invalid CSRF token', 403);
}
```

Or use `csrf_protect()` to enforce CSRF validation automatically for POST requests:

```php
function signin() {
    csrf_protect();

    $email = request_post('email', 'string');
    $password = request_post('password', 'string');

    // ... auth logic ...
}
```

For JavaScript-driven pages, you can also expose the token via meta tag:

```php
<?= csrf_meta_tag() ?>
```

## Examples

### Simple Form Submission

**HTML:**
```html
<form method="POST">
    <input type="text" name="username" required>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button>Register</button>
</form>
```

**PHP:**
```php
function register() {
    if (!request_has_post('username')) {
        session_set('flash', 'Missing username');
        redirect_to('register');
    }

    $username = request_post('username');
    $email = request_post('email', 'email');
    $password = request_post('password');

    if (!$email) {
        session_set('flash', 'Invalid email');
        redirect_to('register');
    }

    $id = db_insert('users', [
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    session_set('flash', 'User registered');
    redirect_to('login');
}
```

### Search with Filters

**HTML:**
```html
<form method="GET">
    <input type="text" name="q" placeholder="Search...">
    <select name="category">
        <option value="">All</option>
        <option value="news">News</option>
        <option value="blog">Blog</option>
    </select>
    <button>Search</button>
</form>

<?php if (request_has_get('q')): ?>
    <h2>Results for "<?= html(request_get('q')) ?>"</h2>
    <!-- Display results -->
<?php endif; ?>
```

**PHP:**
```php
function search() {
    $query = request_get('q');
    $category = request_get('category');
    
    if (!$query) {
        load_view('search/index');
        return;
    }

    $sql = "SELECT * FROM posts WHERE title LIKE ? ";
    $params = ["%$query%"];

    if ($category) {
        $sql .= "AND category = ? ";
        $params[] = $category;
    }

    $results = db_find_all($sql, $params);
    load_view('search/results', ['results' => $results, 'query' => $query]);
}
```

### JSON API with Validation

```php
function create_post() {
    $title = request_post('title');
    $content = request_post('content');
    $publish = request_post('publish', 'bool');

    if (!$title || !$content) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    $post_id = db_insert('posts', [
        'title' => $title,
        'content' => $content,
        'published' => $publish ? 1 : 0,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    http_response_code(201);
    echo json_encode(['id' => $post_id, 'success' => true]);
}
```

### Pagination

```php
function index() {
    $page = request_get('page', 'int') ?? 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;

    $posts = db_find_all(
        "SELECT * FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?",
        [$per_page, $offset]
    );

    $total = db_get_scalar("SELECT COUNT(*) FROM posts");
    $total_pages = ceil($total / $per_page);

    load_view('posts/index', [
        'posts' => $posts,
        'page' => $page,
        'total_pages' => $total_pages
    ]);
}
```

## Security Notes

- **All string values are HTML-escaped** by default using `htmlspecialchars()`
- **No data is trusted** from user input
- **Use type validation** when you know the expected type
- **Always escape output** with `html()` in templates
- **Combine with parameterized queries** for SQL safety
- **Validate email with 'email' type** for email inputs
- **Validate URL with 'url' type** for URL inputs

## Migration from Old Code

**Old Code:**
```php
Request::post('name')
Request::get('page')
Session::get('user_id')
```

**New Code:**
```php
request_post('name')
request_get('page', 'int')
session_get('user_id')
```
