<!-- Modal Settings / Pengaturan Tampilan -->
<div id="modalSettings" class="fixed inset-0 z-50 hidden flex items-end sm:items-center justify-center p-4 sm:p-0">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0 modal-overlay" onclick="closeModal('modalSettings')"></div>

    <div class="bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform translate-y-full sm:translate-y-0 sm:scale-95 transition-all duration-300 z-10 modal-content border border-gray-100 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 sticky top-0 z-10">
            <h2 class="text-sm font-black text-gray-800 uppercase tracking-wider flex items-center gap-2">
                ⚙️ Pengaturan Tampilan
            </h2>
            <button type="button" onclick="closeModal('modalSettings')" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-xl cursor-pointer transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-5 overflow-y-auto no-scrollbar space-y-6">
            
            <!-- Mode Gelap Toggle -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Mode Gelap (Malam)</h3>
                    <p class="text-xs text-gray-500">Ubah warna layar agar tidak silau</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleDarkMode" class="sr-only peer" onchange="toggleTheme()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                </label>
            </div>

            <!-- Teks Besar Toggle -->
            <div class="flex items-center justify-between border-t border-gray-100 pt-5">
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Teks Besar</h3>
                    <p class="text-xs text-gray-500">Perbesar tulisan agar mudah dibaca</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleLargeText" class="sr-only peer" onchange="toggleLargeText()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600 shadow-inner"></div>
                </label>
            </div>

        </div>

    </div>
</div>
