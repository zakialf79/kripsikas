<?php
/**
 * KrispiKas - Model Histori Gudang
 * 
 * Mengelola log riwayat alur bahan di gudang.
 */
class HistoriGudang
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil 50 log terbaru.
     */
    public function getRecent(int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT log_teks FROM histori_gudang ORDER BY id DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Tambah log baru.
     */
    public function insert(string $logTeks): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO histori_gudang (log_teks) VALUES (:log_teks)"
        );
        return $stmt->execute([':log_teks' => $logTeks]);
    }
}
