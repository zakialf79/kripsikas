/**
 * KrispiKas - Mitra Module
 * 
 * Mengelola daftar mitra konsinyasi dan pelanggan langsung.
 */

// ============================================
// RENDER DAFTAR MITRA
// ============================================

function renderKelolaAgen() {
    // Render list konsinyasi
    let htmlKon = '';
    globalState.agenKonsinyasi.forEach(agen => {
        htmlKon += `<div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg border border-gray-200">
            <span class="font-bold text-gray-800 text-xs">${agen}</span>
            <button type="button" onclick="hapusAgen('${agen}', 'kon')" class="text-red-500 font-bold px-2 py-1 text-[10px] bg-red-100 rounded-lg cursor-pointer btn-press">Hapus</button>
        </div>`;
    });
    document.getElementById('listAgenKonVisual').innerHTML = htmlKon || '<p class="text-gray-400 text-center italic">Belum ada mitra.</p>';

    // Render list langsung
    let htmlLsg = '';
    globalState.agenLangsung.forEach(agen => {
        htmlLsg += `<div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg border border-gray-200">
            <span class="font-bold text-gray-800 text-xs">${agen}</span>
            <button type="button" onclick="hapusAgen('${agen}', 'lsg')" class="text-red-500 font-bold px-2 py-1 text-[10px] bg-red-100 rounded-lg cursor-pointer btn-press">Hapus</button>
        </div>`;
    });
    document.getElementById('listAgenLsgVisual').innerHTML = htmlLsg || '<p class="text-gray-400 text-center italic">Belum ada pelanggan.</p>';
}

// ============================================
// TAMBAH MITRA BARU
// ============================================

async function tambahAgen(e, tipe) {
    e.preventDefault();

    const input = document.getElementById(tipe === 'kon' ? 'inputNamaKon' : 'inputNamaLsg');
    const namaBaru = input.value.trim();
    if (!namaBaru) return;

    try {
        await fetch(`${API_URL}?action=tambah_mitra`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nama: namaBaru, tipe: tipe })
        });

        input.value = '';
        showToast(`Mitra "${namaBaru}" berhasil ditambahkan!`, '✅');
        loadDataDariMySQL();
    } catch (err) {
        showToast('Gagal menambah mitra!', '❌');
    }
}

// ============================================
// HAPUS MITRA
// ============================================

async function hapusAgen(nama, tipe) {
    const ok = await customConfirm(`Hapus nama [${nama}] dari daftar database?`, '🗑️', 'Ya, Hapus');
    if (ok) {
        try {
            await fetch(`${API_URL}?action=hapus_mitra`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nama: nama })
            });

            showToast(`Mitra "${nama}" berhasil dihapus.`, '🗑️');
            loadDataDariMySQL();
        } catch (err) {
            showToast('Gagal menghapus mitra!', '❌');
        }
    }
}
