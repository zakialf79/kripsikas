<!-- KrispiKas - Modal Catat Uang Keluar (Kredit) -->
<div id="modalKredit" class="fixed inset-0 z-[60] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative m-2 modal-content">
        <div class="flex justify-between items-center p-4 border-b border-gray-100 bg-gray-50 rounded-t-3xl">
            <h3 class="text-sm font-black text-red-700 uppercase tracking-wider">📤 Catat Uang Keluar</h3>
            <button type="button" onclick="closeModal('modalKredit')" class="text-gray-400 text-xl font-bold cursor-pointer">✕</button>
        </div>
        <form onsubmit="simpanKredit(event)" class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" id="kreditTanggal" required class="w-full p-2.5 border border-gray-200 rounded-xl bg-gray-50 text-xs focus:outline-red-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Kategori Belanja</label>
                    <select id="kreditKategori" onchange="gantiKategoriKredit()" class="w-full p-2.5 border border-gray-200 rounded-xl bg-white font-bold text-xs focus:outline-red-500">
                        <option value="Kulit Mentah">🥩 Kulit Mentah</option>
                        <option value="Minyak">💧 Minyak Goreng</option>
                        <option value="Gas">🔥 Gas Tabung</option>
                        <option value="Plastik">🛍️ Kebutuhan Plastik</option>
                        <option value="Lainnya">✏️ Lainnya</option>
                    </select>
                </div>
            </div>
            
            <div id="wrapperUkuranPlastik" class="hidden">
                <label class="block text-[11px] font-bold text-gray-500 mb-1">Pilih Ukuran Plastik</label>
                <select id="kreditUkuranPlastik" class="w-full p-3 border border-gray-200 rounded-xl bg-white font-medium text-xs">
                    <option value="Plastik 9x16">9 × 16</option>
                    <option value="Plastik 8x13">8 × 13</option>
                    <option value="Plastik 35x55">35 × 55</option>
                    <option value="Ziplock 20x29">Ziplock 20 × 29</option>
                </select>
            </div>

            <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                <label class="block text-[10px] font-black text-red-800 uppercase tracking-wider mb-1">Volume Dibeli (Masuk Gudang)</label>
                <div class="flex gap-2 items-center">
                    <input type="number" id="kreditVolumeBeli" placeholder="0" class="flex-1 p-2.5 border border-gray-200 bg-white rounded-xl text-xs font-bold text-center focus:outline-red-500">
                    <select id="kreditSatuanBeli" class="p-2.5 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 bg-white focus:outline-red-500">
                        <option value="Kg">Kg</option>
                        <option value="Liter">Liter (L)</option>
                        <option value="Tabung">Tabung</option>
                        <option value="Pack">Pack</option>
                    </select>
                </div>
            </div>

            <input type="text" id="kreditKeteranganTambahan" placeholder="Tulis keterangan tambahan belanja..." class="w-full p-3 border border-gray-200 rounded-xl text-xs focus:outline-red-500">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Jumlah Uang Keluar (Rp)</label>
                <input type="text" id="kreditJumlahUang" placeholder="0" required oninput="formatInputRupiah(this)" class="w-full p-3 border-2 border-red-500 rounded-xl font-black text-red-700 text-xl text-center tracking-widest focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white p-3.5 font-bold rounded-xl shadow-md cursor-pointer transition-all text-base btn-press">SIMPAN TRANSAKSI KELUAR</button>
        </form>
    </div>
</div>
