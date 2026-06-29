<!-- KrispiKas - Modal Manajemen Logistik Gudang -->
<div id="modalGudang" class="fixed inset-0 z-[70] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative max-h-[94vh] flex flex-col m-2 modal-content">
        <div class="flex justify-between items-center p-4 border-b border-indigo-200 bg-indigo-50 rounded-t-3xl shrink-0">
            <h3 class="text-sm font-black text-indigo-700 uppercase tracking-wider">📦 Manajemen Logistik Gudang</h3>
            <button type="button" onclick="closeModal('modalGudang')" class="text-gray-400 text-xl font-bold cursor-pointer leading-none">✕</button>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 no-scrollbar">
            
            <!-- Form Catat Proses Masak -->
            <form onsubmit="simpanProduksi(event)" class="bg-gradient-to-br from-indigo-50 to-indigo-100/30 p-4 rounded-2xl border border-indigo-100 shadow-inner space-y-3 shrink-0">
                <h4 class="font-black text-xs text-indigo-800 uppercase tracking-wider text-center">⚙️ Catat Proses Masak (Potong Bahan)</h4>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-wider mb-1 text-center">Mentah</label>
                        <input type="number" id="prodBahanKeluar" placeholder="Kg" step="any" class="w-full p-2 border border-indigo-200 rounded-xl bg-white text-xs font-bold text-center focus:outline-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-wider mb-1 text-center">Minyak</label>
                        <input type="number" id="prodMinyakKeluar" placeholder="Kg" step="any" class="w-full p-2 border border-indigo-200 rounded-xl bg-white text-xs font-bold text-center focus:outline-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-wider mb-1 text-center">Gas</label>
                        <input type="number" id="prodGasKeluar" placeholder="Tbg" step="any" class="w-full p-2 border border-indigo-200 rounded-xl bg-white text-xs font-bold text-center focus:outline-indigo-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 text-xs font-black rounded-xl cursor-pointer shadow transition-all tracking-wider uppercase btn-press">💾 SIMPAN PEMAKAIAN BAHAN</button>
            </form>

            <!-- Akumulasi Pemakaian Bulanan -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-4 rounded-2xl text-white border border-slate-700 shadow-md space-y-2 shrink-0">
                <h4 class="font-black text-[10px] text-blue-400 uppercase tracking-widest border-b border-slate-700 pb-1.5 flex items-center gap-1.5">
                    <span>📊</span> TOTAL AKUMULASI BAHAN TERPAKAI (BULAN INI)
                </h4>
                <div class="grid grid-cols-3 gap-2 text-center pt-1">
                    <div class="bg-white/5 p-2 rounded-xl border border-white/5">
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Mentah</p>
                        <p class="text-base font-black text-amber-400"><span id="totalPakaiMentah">0</span> <span class="text-[9px] text-gray-300 font-normal">Kg</span></p>
                    </div>
                    <div class="bg-white/5 p-2 rounded-xl border border-white/5">
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Minyak</p>
                        <p class="text-base font-black text-teal-400"><span id="totalPakaiMinyak">0</span> <span class="text-[9px] text-gray-300 font-normal">Kg</span></p>
                    </div>
                    <div class="bg-white/5 p-2 rounded-xl border border-white/5">
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Gas</p>
                        <p class="text-base font-black text-orange-400"><span id="totalPakaiGas">0</span> <span class="text-[9px] text-gray-300 font-normal">Tbg</span></p>
                    </div>
                </div>
            </div>

            <!-- Daftar Stok Gudang -->
            <div>
                <div class="flex justify-between items-center mb-2 border-b border-gray-100 pb-1">
                    <h4 class="font-black text-xs text-gray-700 uppercase tracking-wider flex items-center gap-1"><span>📋</span> Sisa Bahan Baku Gudang</h4>
                    <button type="button" onclick="tambahJenisBahanBaku()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black px-2.5 py-1 rounded-xl shadow cursor-pointer btn-press">tambah +</button>
                </div>
                <div id="listStokLengkap" class="space-y-1.5 text-xs max-h-32 overflow-y-auto"></div>
            </div>

            <!-- Log Histori -->
            <div class="border-t border-gray-100 pt-3">
                <h4 class="font-black text-xs text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1"><span>📜</span> Log Riwayat Alur Bahan</h4>
                <div id="historiGudangVisual" class="space-y-1.5 text-[10px] max-h-36 overflow-y-auto bg-gray-50 p-3 rounded-2xl border border-gray-200 font-mono text-gray-600 no-scrollbar"></div>
            </div>
        </div>
    </div>
</div>
