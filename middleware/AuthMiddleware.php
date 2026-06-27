<?php
/**
 * KrispiKas - Auth Middleware
 * 
 * Memastikan user sudah login sebelum mengakses resource.
 * Dipakai untuk mengamankan API endpoints dan halaman dashboard.
 */
class AuthMiddleware
{
    /**
     * Cek apakah user sudah login via session.
     * Untuk API calls: return JSON error jika belum login.
     * Untuk page request: redirect ke halaman login.
     */
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['krispikas_logged_in']) || $_SESSION['krispikas_logged_in'] !== true) {
            // Cek apakah ini API request (ada parameter action)
            if (isset($_GET['action'])) {
                header('Content-Type: application/json; charset=UTF-8');
                http_response_code(401);
                echo json_encode([
                    'error' => 'Unauthorized',
                    'message' => 'Silakan login terlebih dahulu.'
                ]);
                exit;
            }

            // Untuk page request, redirect ke login
            header('Location: index.php');
            exit;
        }
    }

    /**
     * Cek apakah user sudah login (tanpa redirect/exit).
     * Mengembalikan true/false.
     */
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['krispikas_logged_in']) && $_SESSION['krispikas_logged_in'] === true;
    }
}
