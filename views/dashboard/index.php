<!-- =============================================
     KrispiKas - Dashboard Utama
     ============================================= -->

<!-- Tombol Aksi Atas -->
<div class="grid grid-cols-2 gap-3">
    <button type="button" onclick="bukaStatistikDSS()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-3.5 rounded-2xl shadow-md font-bold text-xs flex justify-center items-center gap-2 cursor-pointer active:scale-95 transition-all btn-press">
        <span>📊 Analisis & Statistik</span>
    </button>
    <button type="button" onclick="bukaArsipBulanan()" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-3.5 rounded-2xl shadow-md font-bold text-xs flex justify-center items-center gap-2 cursor-pointer active:scale-95 transition-all btn-press">
        <span>📂 Arsip & Download</span>
    </button>
</div>

<!-- Kartu Stok Gudang -->
<section class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 space-y-3 card-glow">
    <div class="flex justify-between items-center mb-2 border-b border-gray-100 pb-2">
        <div class="flex items-center gap-1.5">
            <span class="text-base">📦</span>
            <h2 class="text-xs font-black text-gray-700 uppercase tracking-wider">Gudang Bahan Baku</h2>
        </div>
        <button type="button" onclick="openModal('modalGudang')" class="text-[10px] bg-indigo-50 text-indigo-700 border border-indigo-100 px-3 py-1.5 rounded-xl font-bold cursor-pointer active:scale-95 transition-all">
            Kelola & Histori ➔
        </button>
    </div>
    <div class="grid grid-cols-3 gap-3 text-center text-[10px] font-bold text-gray-700">
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100/50 p-3 rounded-2xl border border-indigo-100/40">
            <p class="text-indigo-500 text-[10px] mb-0.5">Kulit Mentah</p>
            <span id="stokKulitMentah" class="text-lg font-black text-indigo-900 num-transition">0</span> <span class="text-gray-500 font-normal">Kg</span>
        </div>
        <div class="bg-gradient-to-br from-teal-50 to-teal-100/50 p-3 rounded-2xl border border-teal-100/40">
            <p class="text-teal-600 text-[10px] mb-0.5">Minyak Goreng</p>
            <span id="stokMinyak" class="text-lg font-black text-teal-900 num-transition">0</span> <span class="text-gray-500 font-normal">L</span>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-amber-100/50 p-3 rounded-2xl border border-amber-100/40">
            <p class="text-orange-600 text-[10px] mb-0.5">Gas Tabung</p>
            <span id="stokGas" class="text-lg font-black text-orange-900 num-transition">0</span> <span class="text-gray-500 font-normal">Tbg</span>
        </div>
    </div>
</section>

<!-- Saldo Kas -->
<div class="bg-gradient-to-br from-white to-gray-50/50 p-5 rounded-3xl shadow-sm border border-gray-100 text-center relative overflow-hidden card-glow">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">💰 SALDO KAS SAAT INI</p>
    <p id="dashSaldoAkhir" class="text-3xl font-black text-amber-600 mt-1 drop-shadow-sm num-transition">Rp 0</p>
</div>

<!-- Kelola Mitra -->
<button type="button" onclick="openModal('modalKelolaAgen')" class="w-full bg-white border border-gray-200 text-gray-700 p-3.5 rounded-2xl shadow-sm font-bold text-xs flex justify-between items-center cursor-pointer hover:bg-gray-50 active:scale-95 transition-all btn-press">
    <span class="flex items-center gap-2">⚙️ Kelola Nama Mitra / Pelanggan</span>
    <span class="text-gray-400">➔</span>
</button>

<!-- Tombol Uang Masuk / Keluar -->
<div class="grid grid-cols-2 gap-4">
    <button type="button" onclick="openPilihanUangMasuk()" class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-4 rounded-2xl shadow-md active:scale-95 text-center cursor-pointer transition-all btn-press">
        <p class="text-2xl mb-1">📥</p>
        <p class="font-black text-xs uppercase tracking-wider">Uang Masuk</p>
    </button>
    <button type="button" onclick="openModal('modalKredit')" class="bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-2xl shadow-md active:scale-95 text-center cursor-pointer transition-all btn-press">
        <p class="text-2xl mb-1">📤</p>
        <p class="font-black text-xs uppercase tracking-wider">Uang Keluar</p>
    </button>
</div>

<!-- Tabel Buku Kas -->
<section id="areaCetakBukuKas" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-1">
    <div class="p-4 bg-gray-50/70 border-b border-gray-100 flex justify-between items-center id-anti-cetak">
        <h3 class="text-xs font-black text-gray-700 uppercase tracking-wider">Tabel Catatan Kas</h3>
        <button type="button" onclick="tutupBukuBulanan()" class="text-[10px] bg-amber-600 text-white px-3 py-1.5 rounded-xl font-bold active:scale-95 cursor-pointer shadow transition-all btn-press">🔒 Tutup Buku</button>
    </div>
    <div class="hidden show-on-pdf p-4 text-center border-b-2 border-gray-800">
        <h2 class="text-xl font-black uppercase">Laporan Catatan Buku Kas KrispiKas</h2>
        <p class="text-xs text-gray-600 mt-0.5">Sistem Pencatatan Finansial Internal Usaha Kerupuk</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-amber-50/60 text-amber-900 border-b border-amber-100 font-bold text-[10px] uppercase tracking-wider">
                    <th class="p-3 text-center w-12">Tgl</th>
                    <th class="p-3">Keterangan</th>
                    <th class="p-3 text-right w-20">Debet</th>
                    <th class="p-3 text-right w-20">Kredit</th>
                    <th class="p-3 text-right w-24">Saldo</th>
                    <th class="p-3 text-center w-12 id-anti-cetak">Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyTabelKas" class="divide-y divide-gray-100"></tbody>
        </table>
    </div>
    <div class="p-3 bg-gray-50/50 text-center border-t border-gray-100 id-anti-cetak">
        <button type="button" onclick="resetBukuKas()" class="text-[10px] text-red-500 font-bold px-3 py-1.5 hover:bg-red-50 rounded-xl cursor-pointer transition-all">⚠ Kosongkan Seluruh Tabel Buku Kas</button>
    </div>
</section>
