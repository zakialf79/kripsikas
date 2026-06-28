<!-- KrispiKas - Modal Kelola Mitra & Pelanggan -->
<div id="modalKelolaAgen" class="fixed inset-0 z-[80] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative max-h-[88vh] flex flex-col m-2 modal-content">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 rounded-t-3xl shrink-0">
            <h3 class="text-sm font-black text-gray-700 uppercase tracking-wider">⚙️ Kelola Mitra & Pelanggan</h3>
            <button type="button" onclick="closeModal('modalKelolaAgen')" class="text-gray-400 text-xl font-bold cursor-pointer">✕</button>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 no-scrollbar">
            <!-- Konsinyasi -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50/40 p-4 rounded-2xl border border-blue-100">
                <h4 class="font-black text-xs text-blue-800 uppercase tracking-wider mb-2">🤝 Konsinyasi (Titip Warung)</h4>
                <form onsubmit="tambahAgen(event, 'kon')" class="flex gap-2 mb-2">
                    <input type="text" id="inputNamaKon" required placeholder="Nama warung baru..." class="flex-1 p-2.5 bg-white border border-blue-200 rounded-xl text-xs focus:outline-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-4 font-bold rounded-xl text-xs cursor-pointer hover:bg-blue-700 btn-press">Tambah</button>
                </form>
                <div id="listAgenKonVisual" class="space-y-1.5 max-h-32 overflow-y-auto text-xs bg-white p-2 rounded-xl border border-blue-100/60"></div>
            </div>
            <!-- Langsung / Tunai -->
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50/40 p-4 rounded-2xl border border-emerald-100">
                <h4 class="font-black text-xs text-emerald-800 uppercase tracking-wider mb-2">💵 Pelanggan Tunai (Hotel/RM/Personal)</h4>
                <form onsubmit="tambahAgen(event, 'lsg')" class="flex gap-2 mb-2">
                    <input type="text" id="inputNamaLsg" required placeholder="Nama pelanggan baru..." class="flex-1 p-2.5 bg-white border border-emerald-200 rounded-xl text-xs focus:outline-emerald-500">
                    <button type="submit" class="bg-emerald-600 text-white px-4 font-bold rounded-xl text-xs cursor-pointer hover:bg-emerald-700 btn-press">Tambah</button>
                </form>
                <div id="listAgenLsgVisual" class="space-y-1.5 max-h-32 overflow-y-auto text-xs bg-white p-2 rounded-xl border border-emerald-100/60"></div>
            </div>
        </div>
    </div>
</div>
