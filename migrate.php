<?php

try {
    $config = require_once __DIR__ . '/config/database.php';
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['db_name']};charset={$config['charset']}";
    $conn = new PDO($dsn, $config['username'], $config['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Execute the full SQL dump
    $conn->exec($sql);
    
    echo "<h1>Import Database Berhasil! ✅</h1>";
    echo "<p>Semua tabel dan data telah sukses dimasukkan ke dalam database Railway Anda.</p>";
    echo "<p>Silakan kembali ke <a href='/'>halaman utama website Anda</a>.</p>";

} catch (Exception $e) {
    echo "<h1>Gagal mengimport database ❌</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
