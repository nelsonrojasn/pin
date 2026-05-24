<?php

/**
 * Database Configuration (SQLite3)
 * 
 * Default engine: SQLite3 with performance optimizations
 * - WAL mode for better concurrency
 * - 64MB cache size
 * - Memory-mapped I/O
 * 
 * Usage:
 *   $rows = db_find_all("SELECT * FROM users WHERE id = ?", [1]);
 *   $user = db_find_first("SELECT * FROM users WHERE id = ?", [1]);
 *   $id = db_insert("users", ["name" => "John", "email" => "john@example.com"]);
 *   db_update("users", ["name" => "Jane"], "WHERE id = :id");
 *   db_delete("users", "WHERE id = :id");
 */

// Configuration now in bootstrap.php
// DB_PATH, DB_HOST, DB_USER, DB_PASS 