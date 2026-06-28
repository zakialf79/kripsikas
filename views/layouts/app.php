<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIGMA - Sistem Integrasi Gudang dan Manajemen Keuangan.">
    <title>SIGMA — Sistem Integrasi Gudang dan Manajemen Keuangan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="public/css/app.css?v=<?= time() ?>">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900 text-sm overflow-x-hidden no-scrollbar">

    <?php require_once __DIR__ . '/../components/header.php'; ?>

    <main class="p-3 space-y-4 max-w-md mx-auto mb-24">
        <?php require_once __DIR__ . '/../dashboard/index.php'; ?>
    </main>

    <!-- Modals -->
    <?php require_once __DIR__ . '/../components/modal_debet.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_mitra.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_kredit.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_gudang.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_arsip.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_dss.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_confirm.php'; ?>
    <?php require_once __DIR__ . '/../components/modal_settings.php'; ?>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="toast">
        <div class="bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-xl text-xs font-bold flex items-center gap-2">
            <span id="toastIcon">✅</span>
            <span id="toastMessage">Berhasil disimpan!</span>
        </div>
    </div>

    <!-- JavaScript Modules -->
    <script src="public/js/helpers.js?v=<?= time() ?>"></script>
    <script src="public/js/app.js?v=<?= time() ?>"></script>
    <script src="public/js/kas.js?v=<?= time() ?>"></script>
    <script src="public/js/gudang.js?v=<?= time() ?>"></script>
    <script src="public/js/mitra.js?v=<?= time() ?>"></script>
    <script src="public/js/arsip.js?v=<?= time() ?>"></script>
    <script src="public/js/dss.js?v=<?= time() ?>"></script>
</body>
</html>
