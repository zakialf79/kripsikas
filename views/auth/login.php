<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KrispiKas - Login ke sistem pencatatan usaha kerupuk digital.">
    <title>KrispiKas — Masuk</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="public/css/app.css">
</head>
<body class="m-0 p-0 overflow-hidden">
    <div class="fixed inset-0 bg-gradient-to-br from-amber-500 via-amber-600 to-amber-800 z-[100] flex flex-col items-center justify-center p-6 text-white">
        
        <div class="w-full max-w-sm text-center space-y-6 bg-white/10 p-8 rounded-3xl backdrop-blur-md shadow-xl border border-white/20">
            <div>
                <h1 class="text-3xl font-black tracking-tight">KrispiKas 🍘</h1>
                <p class="text-xs text-amber-100 mt-1">Sistem Pencatatan Usaha Kerupuk Digital</p>
            </div>

            <form id="formLogin" onsubmit="prosesLogin(event)" class="space-y-3 text-gray-800">
                <input type="password" id="inputSandi" placeholder="Masukkan Sandi..." required 
                    autocomplete="current-password"
                    class="w-full p-4 rounded-2xl text-center text-xl font-bold tracking-widest bg-white shadow-inner focus:outline-none focus:ring-2 focus:ring-amber-400">
                
                <button type="submit" id="btnLogin" 
                    class="w-full bg-amber-900 text-white p-4 font-bold rounded-2xl text-lg active:scale-95 transition-transform cursor-pointer shadow-md hover:bg-amber-950 flex justify-center items-center gap-2">
                    <span id="btnLoginText">Masuk Aplikasi ➔</span>
                    <span id="btnLoginSpinner" class="spinner hidden"></span>
                </button>
            </form>

            <p id="pesanSalah" class="text-red-200 font-bold text-sm hidden bg-red-500/20 py-2 rounded-xl">
                ⚠️ Sandi salah, coba lagi.
            </p>
        </div>

        <p class="text-amber-200/50 text-[10px] mt-6">KrispiKas v2.0 — UMKM Digital</p>
    </div>

    <script>
        async function prosesLogin(e) {
            e.preventDefault();

            const password = document.getElementById('inputSandi').value;
            const btnText = document.getElementById('btnLoginText');
            const btnSpinner = document.getElementById('btnLoginSpinner');
            const pesanSalah = document.getElementById('pesanSalah');

            // Show loading
            btnText.textContent = 'Memproses...';
            btnSpinner.classList.remove('hidden');
            pesanSalah.classList.add('hidden');

            try {
                const res = await fetch('index.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ password: password })
                });

                const data = await res.json();

                if (data.status === 'success') {
                    // Redirect ke dashboard
                    window.location.href = 'index.php';
                } else {
                    pesanSalah.classList.remove('hidden');
                    pesanSalah.textContent = '⚠️ ' + (data.message || 'Sandi salah, coba lagi.');
                    btnText.textContent = 'Masuk Aplikasi ➔';
                    btnSpinner.classList.add('hidden');
                }
            } catch (err) {
                pesanSalah.classList.remove('hidden');
                pesanSalah.textContent = '⚠️ Gagal terhubung ke server. Pastikan XAMPP aktif.';
                btnText.textContent = 'Masuk Aplikasi ➔';
                btnSpinner.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
