<?php
/**
 * KrispiKas - Dashboard Controller
 * 
 * Render halaman utama dashboard.
 */
class DashboardController
{
    /**
     * Tampilkan halaman dashboard utama.
     * Hanya bisa diakses jika sudah login (dicek di router).
     */
    public function index(): void
    {
        require_once __DIR__ . '/../views/layouts/app.php';
    }
}
