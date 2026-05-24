<?php

/**
 * Database functions for SQLite3
 * Optimized for speed with WAL mode and performance PRAGMAs
 */

$_db_connection = null;

/**
 * Get or create database connection
 * Applies performance optimizations for SQLite3
 */
function db_connection()
{
    global $_db_connection;
    
    if ($_db_connection !== null) {
        return $_db_connection;
    }

    try {
        $_db_connection = new PDO(
            DB_HOST,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        // Performance optimizations for SQLite3
        $_db_connection->exec('PRAGMA journal_mode = WAL');           // Write-Ahead Logging
        $_db_connection->exec('PRAGMA synchronous = NORMAL');         // Balanced speed/safety
        $_db_connection->exec('PRAGMA cache_size = -64000');          // 64MB cache
        $_db_connection->exec('PRAGMA temp_store = MEMORY');          // Use RAM for temp
        $_db_connection->exec('PRAGMA mmap_size = 30000000');         // Memory-mapped I/O
        $_db_connection->exec('PRAGMA page_size = 4096');             // Page size
        $_db_connection->exec('PRAGMA busy_timeout = 5000');          // 5s timeout

        return $_db_connection;
    } catch (PDOException $e) {
        throw new Exception("Database connection error: " . $e->getMessage());
    }
}

/**
 * Execute prepared statement
 */
function db_exec(string $sql, ?array $params = null)
{
    $stmt = db_connection()->prepare($sql);
    $stmt->execute($params ?? []);
    return $stmt;
}

/**
 * Find all rows
 */
function db_find_all(string $sql, ?array $params = null): array
{
    $stmt = db_exec($sql, $params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Find first row
 */
function db_find_first(string $sql, ?array $params = null): ?array
{
    $rows = db_find_all($sql, $params);
    return count($rows) > 0 ? $rows[0] : null;
}

/**
 * Get scalar value
 */
function db_get_scalar(string $sql, ?array $params = null)
{
    $stmt = db_exec($sql, $params);
    $result = $stmt->fetch(PDO::FETCH_NUM);
    return $result ? $result[0] : null;
}

/**
 * Insert row and return last insert ID
 */
function db_insert(string $table, array $data): string
{
    $keys = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_map(fn($k) => ':' . $k, array_keys($data)));
    $sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
    
    db_exec($sql, $data);
    return db_connection()->lastInsertId();
}

/**
 * Update rows
 */
function db_update(string $table, array $data, string $condition = ''): int
{
    $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
    $sql = "UPDATE $table SET $sets";
    
    if (!empty($condition)) {
        $sql .= " $condition";
    }
    
    $stmt = db_exec($sql, $data);
    return $stmt->rowCount();
}

/**
 * Delete rows
 */
function db_delete(string $table, string $condition = ''): int
{
    $sql = "DELETE FROM $table";
    
    if (!empty($condition)) {
        $sql .= " $condition";
    }
    
    $stmt = db_exec($sql);
    return $stmt->rowCount();
}

/**
 * Run raw SQL
 */
function db_query(string $sql, ?array $params = null)
{
    return db_exec($sql, $params);
}

/**
 * Transaction: begin
 */
function db_begin_transaction()
{
    db_connection()->beginTransaction();
}

/**
 * Transaction: commit
 */
function db_commit()
{
    db_connection()->commit();
}

/**
 * Transaction: rollback
 */
function db_rollback()
{
    db_connection()->rollback();
}
