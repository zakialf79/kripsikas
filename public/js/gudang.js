/**
 * KrispiKas - Gudang & Stok Module
 * 
 * Mengelola stok gudang, produksi (pemotongan bahan), dan visual stok.
 */

// ============================================
// SIMPAN PROSES PRODUKSI (POTONG BAHAN)
// ============================================

function simpanProduksi(e) {
    e.preventDefault();

    let bKeluar = cleanNumber(document.getElementById('prodBahanKeluar').value);
    let gKeluar = cleanNumber(document.getElementById('prodGasKeluar').value);
    let mKeluar = cleanNumber(document.getElementById('prodMinyakKeluar').value);

    if (bKeluar === 0 && gKeluar === 0 && mKeluar === 0) {
        return alert('Harap isi nominal pemakaian!');
    }

    // Kurangi stok gudang
    globalState.databaseStok['Kulit Mentah'] -= bKeluar;
    globalState.databaseStok['Gas'] -= gKeluar;
    globalState.databaseStok['Minyak'] -= mKeluar;

    // Tambah akumulasi pemakaian bulan ini
    globalState.akumulasiPakai.mentah += bKeluar;
    globalState.akumulasiPakai.gas += gKeluar;
    globalState.akumulasiPakai.minyak += mKeluar;

    // Reset form
    document.getElementById('prodBahanKeluar').value = '';
    document.getElementById('prodGasKeluar').value = '';
    document.getElementById('prodMinyakKeluar').value = '';

    closeModal('modalGudang');

    kirimStateKeMySQL(`🔥 Proses Masak: Keluar Kulit ${bKeluar}Kg, Gas ${gKeluar}Tbg, Minyak ${mKeluar}Kg`);
}

// ============================================
// EDIT STOK MANUAL
// ============================================

async function editManualStok(namaBaku) {
    const stokBaru = await customPrompt(`Ubah hitungan stok gudang manual untuk [${namaBaku}]:`, globalState.databaseStok[namaBaku]);
    if (stokBaru !== null && !isNaN(stokBaru) && stokBaru !== '') {
        globalState.databaseStok[namaBaku] = parseFloat(stokBaru);
        kirimStateKeMySQL(`✏️ Koreksi Manual: ${namaBaku} disesuaikan ke ${stokBaru}`);
    }
}

// ============================================
// UPDATE VISUAL STOK GUDANG
// ============================================

function updateVisualStok() {
    // Update kartu stok dashboard
    document.getElementById('stokKulitMentah').innerText = globalState.databaseStok['Kulit Mentah'] || 0;
    document.getElementById('stokMinyak').innerText = globalState.databaseStok['Minyak'] || 0;
    document.getElementById('stokGas').innerText = globalState.databaseStok['Gas'] || 0;

    // Update akumulasi pemakaian
    document.getElementById('totalPakaiMentah').innerText = globalState.akumulasiPakai.mentah;
    document.getElementById('totalPakaiMinyak').innerText = globalState.akumulasiPakai.minyak;
    document.getElementById('totalPakaiGas').innerText = globalState.akumulasiPakai.gas;

    // Render daftar lengkap stok di modal gudang
    let htmlList = '';
    for (const [barang, qty] of Object.entries(globalState.databaseStok)) {
        let satuan = (barang === 'Gas') ? 'Tbg' : 'Kg';
        htmlList += `<div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-xl border border-gray-200">
            <span class="font-semibold text-gray-800">${barang}</span>
            <div class="flex items-center gap-3">
                <span class="font-black text-indigo-700">${qty} <span class="text-[9px] text-gray-400 font-normal">${satuan}</span></span>
                <button type="button" onclick="editManualStok('${barang}')" class="text-[9px] bg-white border border-gray-300 px-2.5 py-1.5 rounded-xl shadow-sm cursor-pointer btn-press">Edit</button>
            </div>
        </div>`;
    }
    document.getElementById('listStokLengkap').innerHTML = htmlList;

    // Render histori gudang
    let htmlHistori = '';
    globalState.historiGudang.forEach(h => {
        htmlHistori += `<div class="border-b border-gray-100 pb-1.5 mb-1.5 text-gray-600 last:border-none">${h}</div>`;
    });
    document.getElementById('historiGudangVisual').innerHTML = htmlHistori || '<p class="text-center text-gray-400 italic py-2">Belum ada riwayat alur logistik.</p>';
}
