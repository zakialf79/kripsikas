<?php
/**
 * KrispiKas — Entry Point & Router
 * 
 * Sistem Pencatatan Usaha Kerupuk Digital
 * 
 * File ini menangani:
 * 1. Page routing (login / dashboard)
 * 2. API routing (?action=xxx)
 */

session_start();

// ============================================
// AUTOLOADER — Load class dari models/, controllers/, middleware/
// ============================================
spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
        __DIR__ . '/middleware/',
    ];

    foreach ($directories as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ============================================
// ROUTING
// ============================================
$action = isset($_GET['action']) ? $_GET['action'] : '';
$method = $_SERVER['REQUEST_METHOD'];

// ----- API ROUTES (mengembalikan JSON) -----
if (!empty($action)) {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    try {
        switch ($action) {

            // === AUTH ===
            case 'login':
                $controller = new AuthController();
                $controller->login();
                break;

            case 'logout':
                $controller = new AuthController();
                $controller->logout();
                break;

            // === DATA INIT ===
            case 'get_init_data':
                AuthMiddleware::check();
                $controller = new KasController();
                $controller->getInitData();
                break;

            // === SYNC STATE ===
            case 'sync_all_state':
                AuthMiddleware::check();
                $controller = new KasController();
                $controller->syncAllState();
                break;

            // === MITRA ===
            case 'tambah_mitra':
                AuthMiddleware::check();
                $controller = new MitraController();
                $controller->tambah();
                break;

            case 'hapus_mitra':
                AuthMiddleware::check();
                $controller = new MitraController();
                $controller->hapus();
                break;

            // === ARSIP ===
            case 'get_arsip_bulan':
                AuthMiddleware::check();
                $controller = new ArsipController();
                $controller->getArsipBulan();
                break;

            // === 404 ===
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Action "' . htmlspecialchars($action) . '" tidak ditemukan.']);
                break;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }

    exit;
}

// ----- PAGE ROUTES (mengembalikan HTML) -----
if (AuthMiddleware::isLoggedIn()) {
    // User sudah login → tampilkan dashboard
    $controller = new DashboardController();
    $controller->index();
} else {
    // Belum login → tampilkan halaman login
    require_once __DIR__ . '/views/auth/login.php';
}
