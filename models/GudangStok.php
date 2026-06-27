<?php
/**
 * KrispiKas - Model Gudang Stok
 * 
 * CRUD operations untuk tabel gudang_stok.
 */
class GudangStok
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil semua data stok gudang sebagai key-value (nama_bahan => qty).
     */
    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM gudang_stok");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['nama_bahan']] = (float) $row['qty'];
        }
        return $result;
    }

    /**
     * Insert atau update stok bahan (upsert).
     * Menggunakan ON DUPLICATE KEY UPDATE karena nama_bahan adalah PRIMARY KEY.
     */
    public function upsert(string $namaBahan, float $qty): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO gudang_stok (nama_bahan, qty) VALUES (:bahan, :qty) 
             ON DUPLICATE KEY UPDATE qty = VALUES(qty)"
        );
        $stmt->execute([
            ':bahan' => $namaBahan,
            ':qty'   => $qty,
        ]);
    }

    /**
     * Bulk upsert semua stok dari frontend state.
     */
    public function syncAll(object $stokData): void
    {
        foreach ($stokData as $bahan => $qty) {
            $this->upsert($bahan, (float) $qty);
        }
    }
}
