<?php
/**
 * KrispiKas - Model Buku Kas
 * 
 * CRUD operations untuk tabel buku_kas.
 */
class BukuKas
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil semua data kas bulan berjalan (belum diarsipkan).
     */
    public function getAktif(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, tgl_sort AS tglSort, tgl_visual AS tgl, keterangan AS ket, debet, kredit 
             FROM buku_kas 
             WHERE is_arsip = 0 
             ORDER BY tgl_sort ASC, id ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Ambil data kas yang sudah diarsipkan berdasarkan nama bulan.
     */
    public function getArsip(string $namaBulan): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, tgl_sort AS tglSort, tgl_visual AS tgl, keterangan AS ket, debet, kredit 
             FROM buku_kas 
             WHERE is_arsip = 1 AND nama_bulan_arsip = :bulan 
             ORDER BY tgl_sort ASC, id ASC"
        );
        $stmt->bindParam(':bulan', $namaBulan);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Ambil daftar bulan-bulan yang sudah diarsipkan.
     */
    public function getArsipBulanList(): array
    {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT nama_bulan_arsip FROM buku_kas WHERE is_arsip = 1"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Hapus semua data kas aktif lalu insert ulang (sync dari frontend).
     */
    public function syncBulk(array $rows): void
    {
        // Hapus data aktif dulu
        $stmt = $this->db->prepare("DELETE FROM buku_kas WHERE is_arsip = 0");
        $stmt->execute();

        // Insert semua baris dari frontend
        $stmt = $this->db->prepare(
            "INSERT INTO buku_kas (id, tgl_sort, tgl_visual, keterangan, debet, kredit, is_arsip, nama_bulan_arsip) 
             VALUES (:id, :tgl_sort, :tgl_visual, :keterangan, :debet, :kredit, :is_arsip, :nama_bulan_arsip)"
        );

        foreach ($rows as $row) {
            $stmt->execute([
                ':id'                => $row->id,
                ':tgl_sort'          => $row->tglSort,
                ':tgl_visual'        => $row->tgl,
                ':keterangan'        => $row->ket,
                ':debet'             => $row->debet,
                ':kredit'            => $row->kredit,
                ':is_arsip'          => isset($row->is_arsip) ? $row->is_arsip : 0,
                ':nama_bulan_arsip'  => isset($row->nama_bulan_arsip) ? $row->nama_bulan_arsip : null,
            ]);
        }
    }
}
