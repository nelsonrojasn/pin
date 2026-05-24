# Database Functions - SQLite3

## Configuration

Base de datos configurada en `bootstrap.php`:

```php
define('DB_PATH', ROOT . DS . 'db' . DS . 'app.sqlite');
define('DB_HOST', 'sqlite:' . DB_PATH);
```

## Available Functions

### Connection

```php
$conn = db_connection();  // Get or create connection
```

### SELECT Operations

```php
// Get all rows
$users = db_find_all("SELECT * FROM users");
$users = db_find_all("SELECT * FROM users WHERE id = ?", [1]);

// Get first row
$user = db_find_first("SELECT * FROM users WHERE id = ?", [1]);

// Get scalar value
$count = db_get_scalar("SELECT COUNT(*) FROM users");
$count = db_get_scalar("SELECT COUNT(*) FROM users WHERE role = ?", ["admin"]);
```

### INSERT

```php
$id = db_insert("users", [
    "name" => "John Doe",
    "email" => "john@example.com",
    "role" => "user"
]);
```

### UPDATE

```php
// Update with condition
db_update("users", 
    ["name" => "Jane Doe", "role" => "admin"],
    "WHERE id = :id"
);

// Update all records
db_update("posts", ["status" => "published"]);
```

### DELETE

```php
// Delete with condition
db_delete("users", "WHERE id = :id");

// Delete all records
db_delete("posts");
```

### Raw Query

```php
$stmt = db_query("SELECT * FROM users WHERE created_at > ?", ["2024-01-01"]);
```

### Transactions

```php
db_begin_transaction();
try {
    db_insert("users", ["name" => "John"]);
    db_insert("posts", ["user_id" => 1, "title" => "Hello"]);
    db_commit();
} catch (Exception $e) {
    db_rollback();
    throw $e;
}
```

## Performance Optimizations

SQLite3 is configured with the following PRAGMAs for maximum speed:

- **WAL Mode**: Write-Ahead Logging for better concurrency
- **Synchronous**: NORMAL for balanced speed/safety
- **Cache Size**: 64MB for faster queries
- **Temp Store**: MEMORY for temporary tables in RAM
- **Memory-Mapped I/O**: 30MB for faster file access
- **Busy Timeout**: 5 seconds

## Examples

### Get user by ID

```php
function get_user($id) {
    return db_find_first(
        "SELECT id, name, email FROM users WHERE id = ?",
        [$id]
    );
}
```

### Create new post

```php
function create_post($user_id, $title, $content) {
    return db_insert("posts", [
        "user_id" => $user_id,
        "title" => $title,
        "content" => $content,
        "created_at" => date("Y-m-d H:i:s")
    ]);
}
```

### Update post status

```php
function publish_post($post_id) {
    db_update(
        "posts",
        ["status" => "published", "published_at" => date("Y-m-d H:i:s")],
        "WHERE id = :id"
    );
}
```

### Get posts by user

```php
function user_posts($user_id) {
    return db_find_all(
        "SELECT id, title, status, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC",
        [$user_id]
    );
}

### QueryBuilder (opcional)

Si prefieres una API ligera y procedural para construir SELECT, hay helpers `qb_*` disponibles:

```php
$qb = qb('posts');
$qb = qb_columns($qb, 'p.*, u.username');
$qb = qb_join($qb, 'INNER JOIN users u ON p.user_id = u.id');
$qb = qb_where($qb, 'p.published = 1');
$qb = qb_order_by($qb, 'p.created_at DESC');
$qb = qb_limit($qb, 10);

$sql = qb_to_sql($qb);
$posts = db_find_all($sql);

//otro modo
$sql = qb_select('posts', [
  'columns' => 'p.*, u.username',
  'joins' => ['INNER JOIN users u ON p.user_id = u.id'],
  'where' => 'p.published = 1',
  'order' => 'p.created_at DESC',
  'limit' => 10,
]);
$posts = db_find_all($sql);

```

Estas funciones son opcionales; para consultas muy complejas sigue siendo recomendable escribir SQL directo.
```
