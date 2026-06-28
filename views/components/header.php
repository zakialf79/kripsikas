<!-- SIGMA - Header Component -->
<header class="bg-gradient-to-r from-blue-800 to-cyan-600 text-white p-4 shadow-md sticky top-0 z-40 rounded-b-3xl flex justify-between items-center">
    <div>
        <h1 class="text-base font-black tracking-wide"><span class="text-white">SIGM</span><span class="text-yellow-400">A</span></h1>
        <p class="text-[10px] text-cyan-100" id="visualBulan">Periode Catatan</p>
    </div>
    <div class="flex gap-2">
        <button type="button" onclick="openModal('modalSettings')" class="bg-blue-900/50 hover:bg-blue-900 text-xs px-3 py-2 rounded-xl font-bold cursor-pointer transition-all shadow btn-press" title="Pengaturan Tampilan">⚙️</button>
        <button type="button" onclick="prosesLogout()" class="bg-blue-900/50 hover:bg-blue-900 text-xs px-3 py-2 rounded-xl font-bold cursor-pointer transition-all shadow btn-press">Keluar 🚪</button>
    </div>
</header>
