<?php
/**
 * KrispiKas - Model Akumulasi Pemakaian
 * 
 * Mengelola total pemakaian bahan baku per bulan.
 */
class AkumulasiPakai
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil data akumulasi pemakaian terbaru.
     */
    public function getLatest(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM akumulasi_pakai ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            return [
                'mentah' => (float) $row['total_mentah'],
                'minyak' => (float) $row['total_minyak'],
                'gas'    => (float) $row['total_gas'],
            ];
        }

        return ['mentah' => 0, 'minyak' => 0, 'gas' => 0];
    }

    /**
     * Insert atau update akumulasi pemakaian bulan ini.
     * bulan_tahun adalah UNIQUE, jadi pakai ON DUPLICATE KEY UPDATE.
     */
    public function upsert(float $mentah, float $minyak, float $gas): void
    {
        $periode = date("F Y"); // e.g. "June 2026"

        $stmt = $this->db->prepare(
            "INSERT INTO akumulasi_pakai (bulan_tahun, total_mentah, total_minyak, total_gas) 
             VALUES (:periode, :m, :my, :g) 
             ON DUPLICATE KEY UPDATE 
                total_mentah = VALUES(total_mentah), 
                total_minyak = VALUES(total_minyak), 
                total_gas = VALUES(total_gas)"
        );
        $stmt->execute([
            ':periode' => $periode,
            ':m'       => $mentah,
            ':my'      => $minyak,
            ':g'       => $gas,
        ]);
    }
}
