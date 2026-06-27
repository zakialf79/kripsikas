-- ============================================
-- KrispiKas - Database Schema
-- Sistem Pencatatan Usaha Kerupuk Digital
-- ============================================

CREATE DATABASE IF NOT EXISTS rajo_ameh_db
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE rajo_ameh_db;

-- -----------------------------------------------
-- Tabel Buku Kas (Catatan Keuangan Harian)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS buku_kas (
    id BIGINT PRIMARY KEY,
    tgl_sort VARCHAR(20) NOT NULL,
    tgl_visual VARCHAR(10) NOT NULL,
    keterangan TEXT NOT NULL,
    debet BIGINT DEFAULT 0,
    kredit BIGINT DEFAULT 0,
    is_arsip TINYINT(1) DEFAULT 0,
    nama_bulan_arsip VARCHAR(50) DEFAULT NULL,
    INDEX idx_arsip (is_arsip),
    INDEX idx_bulan_arsip (nama_bulan_arsip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------
-- Tabel Gudang Stok (Persediaan Bahan Baku)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS gudang_stok (
    nama_bahan VARCHAR(100) PRIMARY KEY,
    qty DECIMAL(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------
-- Tabel Mitra (Pelanggan & Agen)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS mitra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mitra VARCHAR(200) NOT NULL,
    tipe_mitra VARCHAR(10) NOT NULL COMMENT 'kon = konsinyasi, lsg = langsung'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------
-- Tabel Histori Gudang (Log Alur Bahan)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS histori_gudang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_teks TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------
-- Tabel Akumulasi Pemakaian Bahan (Per Bulan)
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS akumulasi_pakai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bulan_tahun VARCHAR(50) NOT NULL UNIQUE,
    total_mentah DECIMAL(10,2) DEFAULT 0.00,
    total_minyak DECIMAL(10,2) DEFAULT 0.00,
    total_gas DECIMAL(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------
-- Data Awal Stok Gudang
-- -----------------------------------------------
INSERT IGNORE INTO gudang_stok (nama_bahan, qty) VALUES 
('Kulit Mentah', 0),
('Minyak', 0),
('Gas', 0);
