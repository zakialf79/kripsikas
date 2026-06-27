<!-- KrispiKas - Modal Pilihan Uang Masuk + Form Debet -->

<!-- Modal Pilihan Jalur Uang Masuk -->
<div id="modalPilihanUangMasuk" class="fixed inset-0 z-[60] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl relative p-5 space-y-4 m-2 modal-content">
        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
            <h3 class="text-sm font-black text-emerald-700 uppercase tracking-wider">Pilih Jalur Uang Masuk</h3>
            <button type="button" onclick="closeModal('modalPilihanUangMasuk')" class="text-gray-400 hover:text-gray-600 text-2xl font-bold cursor-pointer">✕</button>
        </div>
        <div class="space-y-3">
            <button type="button" onclick="lanjutFormDebet('Konsinyasi')" class="w-full bg-gradient-to-r from-blue-50 to-blue-100/50 border border-blue-200 p-4 rounded-2xl text-left cursor-pointer active:scale-95 transition-transform btn-press">
                <p class="font-black text-blue-800 text-base">🤝 Titip / Konsinyasi</p>
            </button>
            <button type="button" onclick="lanjutFormDebet('Bayar Langsung')" class="w-full bg-gradient-to-r from-emerald-50 to-emerald-100/50 border border-emerald-200 p-4 rounded-2xl text-left cursor-pointer active:scale-95 transition-transform btn-press">
                <p class="font-black text-emerald-800 text-base">💵 Bayar Langsung</p>
            </button>
        </div>
    </div>
</div>

<!-- Modal Form Debet (Catat Uang Masuk) -->
<div id="modalDebet" class="fixed inset-0 z-[70] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative max-h-[92vh] flex flex-col m-2 modal-content">
        <div class="flex justify-between items-center p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-3xl shrink-0">
            <h3 id="judulFormDebet" class="text-sm font-black text-emerald-700 uppercase tracking-wider">📥 Catat Uang Masuk</h3>
            <button type="button" onclick="kembaliKePilihan()" class="text-gray-400 hover:text-gray-600 text-xl font-bold cursor-pointer">✕</button>
        </div>
        <form onsubmit="simpanDebet(event)" class="p-5 space-y-4 overflow-y-auto flex-1 no-scrollbar">
            <input type="hidden" id="debetMetodePenjualan">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" id="debetTanggal" required class="w-full p-2.5 border border-gray-200 rounded-xl bg-gray-50 font-medium text-xs focus:outline-emerald-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Sistem Bayar</label>
                    <select id="debetMetodeBayar" class="w-full p-2.5 border border-gray-200 rounded-xl bg-white font-bold text-emerald-700 text-xs focus:outline-emerald-500">
                        <option value="Cash">💵 Cash</option>
                        <option value="Transfer">🏦 Transfer</option>
                        <option value="QRIS">📱 QRIS</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Nama Pelanggan / Mitra</label>
                <select id="debetPilihNama" class="w-full p-3 border border-gray-200 rounded-xl bg-white font-semibold text-xs focus:outline-emerald-500"></select>
            </div>

            <div id="blokKonsinyasi" class="hidden p-3.5 bg-gradient-to-br from-blue-50 to-indigo-50/30 rounded-2xl border border-blue-100">
                <label class="block text-[10px] font-black text-blue-800 uppercase tracking-wider mb-1">Varian Pack (Pencatatan)</label>
                <select id="konsinyasiVarian" class="w-full p-2.5 border border-gray-200 rounded-xl bg-white font-medium text-xs focus:outline-blue-500">
                    <option value="Kerupuk 1k">Kerupuk Pack 1.000 (1k)</option>
                    <option value="Kerupuk 2k">Kerupuk Pack 2.000 (2k)</option>
                </select>
            </div>

            <div id="blokBayarLangsung" class="hidden p-3.5 bg-gradient-to-br from-emerald-50 to-teal-50/30 rounded-2xl border border-emerald-100 space-y-2">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 uppercase tracking-wider mb-1">Kondisi</label>
                        <select id="langsungKondisi" onchange="updateOpsiUnitLangsung()" class="w-full p-2.5 border border-gray-200 rounded-xl bg-white font-medium text-xs focus:outline-emerald-500">
                            <option value="Matang">Kerupuk Matang</option>
                            <option value="Mentah">Kulit Mentah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-emerald-800 uppercase tracking-wider mb-1">Satuan</label>
                        <select id="langsungUnit" class="w-full p-2.5 border border-gray-200 rounded-xl bg-white font-medium text-xs focus:outline-emerald-500">
                            <option value="Kg">Per Kg</option>
                            <option value="Pcs">Per Pcs</option>
                            <option value="Pack">Per Pack</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 p-3.5 bg-gray-50 rounded-2xl border border-gray-200">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1 text-center">Jumlah (Qty)</label>
                    <input type="number" id="debetQty" placeholder="0" required oninput="hitungTotalDebet()" class="w-full p-2.5 border border-gray-300 rounded-xl text-base font-black text-center focus:outline-amber-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1">Harga Satuan (Rp)</label>
                    <input type="text" id="debetHargaSatuan" placeholder="0" required oninput="formatInputRupiah(this); hitungTotalDebet()" class="w-full p-2.5 border border-gray-300 rounded-xl text-base font-black focus:outline-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-5 gap-3 items-end">
                <div class="col-span-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Ket. Tambahan</label>
                    <input type="text" id="debetKeteranganTambahan" class="w-full p-3 border border-gray-200 rounded-xl text-xs focus:outline-emerald-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-black text-emerald-800 uppercase tracking-wider mb-1 text-center">Total (Rp)</label>
                    <input type="text" id="debetTotalUangVisual" readonly class="w-full p-3 bg-gray-100 border-2 border-emerald-500 rounded-xl font-black text-emerald-700 text-sm text-center">
                </div>
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white p-4 font-bold rounded-2xl shadow-md cursor-pointer transition-all text-base shrink-0 btn-press">SIMPAN TRANSAKSI MASUK</button>
        </form>
    </div>
</div>
