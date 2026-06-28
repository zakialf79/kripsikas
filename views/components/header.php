<!-- SIGMA - Header Component -->
<header class="bg-gradient-to-r from-blue-800 to-cyan-600 text-white px-3 py-3 sm:p-4 shadow-md sticky top-0 z-40 rounded-b-3xl flex justify-between items-center">
    <div class="flex items-center gap-2 overflow-hidden">
        <img src="public/img/logo.png" alt="Logo SIGMA" class="h-7 sm:h-8 w-auto shrink-0 object-contain drop-shadow-md" onerror="this.style.display='none'">
        <div class="min-w-0">
            <h1 class="text-sm sm:text-base font-black tracking-wide truncate"><span class="text-white">SIGM</span><span class="text-yellow-400">A</span></h1>
            <p class="text-[9px] sm:text-[10px] text-cyan-100 truncate" id="visualBulan">Periode Catatan</p>
        </div>
    </div>
    <div class="flex gap-1.5 sm:gap-2 shrink-0">
        <button type="button" onclick="openModal('modalSettings')" class="bg-blue-900/50 hover:bg-blue-900 text-xs px-2.5 sm:px-3 py-2 rounded-xl font-bold cursor-pointer transition-all shadow btn-press relative z-50" title="Pengaturan Tampilan">⚙️</button>
        <button type="button" onclick="prosesLogout()" class="bg-blue-900/50 hover:bg-blue-900 text-xs px-2.5 sm:px-3 py-2 rounded-xl font-bold cursor-pointer transition-all shadow btn-press relative z-50">Keluar 🚪</button>
    </div>
</header>
