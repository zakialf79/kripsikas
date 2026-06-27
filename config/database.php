<?php
/**
 * KrispiKas - Konfigurasi Database
 * 
 * Ubah nilai di bawah sesuai dengan setup XAMPP/MySQL kamu.
 */
return [
    'host'     => getenv('MYSQLHOST') ?: 'localhost',
    'port'     => getenv('MYSQLPORT') ?: '3306',
    'db_name'  => getenv('MYSQLDATABASE') ?: 'rajo_ameh_db',
    'username' => getenv('MYSQLUSER') ?: 'root',
    'password' => getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : '',
    'charset'  => 'utf8mb4'
];
