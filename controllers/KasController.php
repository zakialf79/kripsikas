<?php
/**
 * KrispiKas - Kas Controller
 * 
 * Menangani API untuk inisialisasi data dan sinkronisasi state buku kas.
 */
class KasController
{
    /**
     * GET: Ambil semua data awal saat aplikasi dimuat.
     * Endpoint: ?action=get_init_data
     */
    public function getInitData(): void
    {
        try {
            $bukuKas       = new BukuKas();
            $gudangStok    = new GudangStok();
            $mitra         = new Mitra();
            $historiGudang = new HistoriGudang();
            $akumulasi     = new AkumulasiPakai();

            $mitraData = $mitra->getAll();

            echo json_encode([
                'listBukuKas'    => $bukuKas->getAktif(),
                'databaseStok'   => $gudangStok->getAll(),
                'agenKonsinyasi' => $mitraData['konsinyasi'],
                'agenLangsung'   => $mitraData['langsung'],
                'historiGudang'  => $historiGudang->getRecent(),
                'arsipBulanList' => $bukuKas->getArsipBulanList(),
                'akumulasiPakai' => $akumulasi->getLatest(),
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal memuat data: ' . $e->getMessage()]);
        }
    }

    /**
     * POST: Sinkronisasi seluruh state dari frontend ke database.
     * Endpoint: ?action=sync_all_state
     */
    public function syncAllState(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request body']);
            return;
        }

        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // 1. Update stok gudang
            $gudangStok = new GudangStok();
            if (isset($data->databaseStok)) {
                $gudangStok->syncAll($data->databaseStok);
            }

            // 2. Simpan log histori gudang (jika ada entri baru)
            $histori = new HistoriGudang();
            if (isset($data->clearHistoriGudang) && $data->clearHistoriGudang === true) {
                $histori->clearAll();
            }
            if (!empty($data->newLogTeks)) {
                $histori->insert($data->newLogTeks);
            }

            // 3. Sinkronisasi buku kas bulan berjalan
            $bukuKas = new BukuKas();
            if (isset($data->listBukuKas)) {
                $bukuKas->syncBulk($data->listBukuKas);
            }

            // 4. Update akumulasi pemakaian bulanan
            if (isset($data->akumulasiPakai)) {
                $akumulasi = new AkumulasiPakai();
                $akumulasi->upsert(
                    $data->akumulasiPakai->mentah ?? 0,
                    $data->akumulasiPakai->minyak ?? 0,
                    $data->akumulasiPakai->gas ?? 0
                );
            }

            $db->commit();
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
