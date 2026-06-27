<?php
/**
 * KrispiKas - Mitra Controller
 * 
 * Menangani API untuk tambah dan hapus mitra/pelanggan.
 */
class MitraController
{
    /**
     * POST: Tambah mitra baru.
     * Endpoint: ?action=tambah_mitra
     * Body: { "nama": "xxx", "tipe": "kon" | "lsg" }
     */
    public function tambah(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->nama) || empty($data->tipe)) {
            http_response_code(400);
            echo json_encode(['error' => 'Nama dan tipe mitra wajib diisi.']);
            return;
        }

        try {
            $mitra = new Mitra();
            $mitra->insert($data->nama, $data->tipe);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * POST: Hapus mitra berdasarkan nama.
     * Endpoint: ?action=hapus_mitra
     * Body: { "nama": "xxx" }
     */
    public function hapus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->nama)) {
            http_response_code(400);
            echo json_encode(['error' => 'Nama mitra wajib diisi.']);
            return;
        }

        try {
            $mitra = new Mitra();
            $mitra->delete($data->nama);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
