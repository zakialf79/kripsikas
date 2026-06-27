<?php
/**
 * KrispiKas - Arsip Controller
 * 
 * Menangani API untuk mengambil data arsip bulanan.
 */
class ArsipController
{
    /**
     * GET: Ambil data arsip berdasarkan nama bulan.
     * Endpoint: ?action=get_arsip_bulan&bulan=Juni%202026
     */
    public function getArsipBulan(): void
    {
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

        if (empty($bulan)) {
            http_response_code(400);
            echo json_encode(['error' => 'Parameter bulan wajib diisi.']);
            return;
        }

        try {
            $bukuKas = new BukuKas();
            $data = $bukuKas->getArsip($bulan);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengambil arsip: ' . $e->getMessage()]);
        }
    }
}
