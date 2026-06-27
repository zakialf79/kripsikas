<?php
/**
 * KrispiKas - Model Mitra
 * 
 * CRUD operations untuk tabel mitra (pelanggan & agen).
 */
class Mitra
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil semua mitra, dipisah berdasarkan tipe.
     * Return: ['konsinyasi' => [...], 'langsung' => [...]]
     */
    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM mitra");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $konsinyasi = [];
        $langsung = [];

        foreach ($rows as $m) {
            if ($m['tipe_mitra'] === 'kon') {
                $konsinyasi[] = $m['nama_mitra'];
            } else {
                $langsung[] = $m['nama_mitra'];
            }
        }

        return [
            'konsinyasi' => $konsinyasi,
            'langsung'   => $langsung,
        ];
    }

    /**
     * Tambah mitra baru.
     */
    public function insert(string $nama, string $tipe): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mitra (nama_mitra, tipe_mitra) VALUES (:nama, :tipe)"
        );
        return $stmt->execute([
            ':nama' => $nama,
            ':tipe' => $tipe,
        ]);
    }

    /**
     * Hapus mitra berdasarkan nama.
     */
    public function delete(string $nama): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mitra WHERE nama_mitra = :nama"
        );
        return $stmt->execute([':nama' => $nama]);
    }
}
