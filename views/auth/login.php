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
    <div class="fixed inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-cyan-700 z-[100] flex flex-col items-center justify-center p-6 text-white">
        
        <div class="w-full max-w-sm text-center space-y-6 bg-white/10 p-8 rounded-3xl backdrop-blur-md shadow-xl border border-white/20">
            <div>
                <h1 class="text-4xl font-black tracking-tight"><span class="text-white">SIGM</span><span class="text-yellow-400">A</span></h1>
                <p class="text-xs text-cyan-100 mt-1">Sistem Integrasi Gudang dan Manajemen Keuangan</p>
            </div>

            <form id="formLogin" onsubmit="prosesLogin(event)" class="space-y-3 text-gray-800">
                <input type="password" id="inputSandi" placeholder="Masukkan Sandi..." required 
                    autocomplete="current-password"
                    class="w-full p-4 rounded-2xl text-center text-xl font-bold tracking-widest bg-white shadow-inner focus:outline-none focus:ring-2 focus:ring-yellow-400">
                
                <button type="submit" id="btnLogin" 
                    class="w-full bg-yellow-500 text-blue-900 p-4 font-black rounded-2xl text-lg active:scale-95 transition-transform cursor-pointer shadow-md hover:bg-yellow-400 flex justify-center items-center gap-2">
                    <span id="btnLoginText">Masuk Aplikasi ➔</span>
                    <span id="btnLoginSpinner" class="spinner hidden"></span>
                </button>
            </form>

            <p id="pesanSalah" class="text-red-200 font-bold text-sm hidden bg-red-500/20 py-2 rounded-xl">
                ⚠️ Sandi salah, coba lagi.
            </p>
        </div>

        <p class="text-cyan-200/50 text-[10px] mt-6 font-bold tracking-wider">By Unand Transformative</p>
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
