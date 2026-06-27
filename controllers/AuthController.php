<?php
/**
 * KrispiKas - Auth Controller
 * 
 * Menangani login dan logout.
 * Password diverifikasi secara server-side, session disimpan di PHP.
 */
class AuthController
{
    // Password aplikasi — ubah di sini jika ingin ganti sandi
    private const APP_PASSWORD = 'ibu123';

    /**
     * Proses login via POST.
     * Menerima JSON body: { "password": "xxx" }
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->password)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Password tidak boleh kosong.']);
            return;
        }

        if ($data->password === self::APP_PASSWORD) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['krispikas_logged_in'] = true;
            $_SESSION['krispikas_login_time'] = time();

            echo json_encode(['status' => 'success', 'message' => 'Login berhasil!']);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Sandi salah, coba lagi.']);
        }
    }

    /**
     * Proses logout — hapus session dan redirect.
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        // Jika dipanggil via AJAX
        if (isset($_GET['action'])) {
            echo json_encode(['status' => 'success', 'message' => 'Logged out.']);
            return;
        }

        // Jika dipanggil langsung via URL
        header('Location: index.php');
        exit;
    }
}
