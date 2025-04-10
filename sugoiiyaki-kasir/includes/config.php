<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi Database SQLite
$db_path = __DIR__ . '/../database.sqlite';

// Membuat koneksi database menggunakan PDO SQLite
try {
    $pdo = new PDO("sqlite:" . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Enable foreign key support
    $pdo->exec('PRAGMA foreign_keys = ON;');
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
