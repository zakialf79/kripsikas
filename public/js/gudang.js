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
// TAMBAH JENIS BAHAN BAKU BARU
// ============================================

async function tambahJenisBahanBaku() {
    const namaBaku = await customPrompt('Masukkan nama jenis plastik / bahan baku baru:');
    if (!namaBaku || !namaBaku.trim()) return;

    const cleanNama = namaBaku.trim();
    if (globalState.databaseStok[cleanNama] !== undefined) {
        return showToast(`Bahan baku "${cleanNama}" sudah ada!`, '⚠️');
    }

    globalState.databaseStok[cleanNama] = 0;
    kirimStateKeMySQL(`➕ Tambah Bahan Baku Baru: ${cleanNama}`);

    // Otomatis buka form catat uang keluar
    updateVisualStok();
    closeModal('modalGudang');
    setTimeout(() => {
        if (typeof bukaFormKredit === 'function') bukaFormKredit(cleanNama);
    }, 300);
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
// HAPUS JENIS BAHAN BAKU
// ============================================

async function hapusJenisBahanBaku(namaBaku) {
    const ok = await customConfirm(`Yakin ingin MENGHAPUS item "${namaBaku}" dari daftar gudang?\n\nPeringatan: Data yang dihapus tidak bisa dikembalikan!`, '🗑️', 'Ya, Hapus');
    if (ok) {
        delete globalState.databaseStok[namaBaku];
        kirimStateKeMySQL(`🗑️ Hapus Bahan Baku: ${namaBaku}`);
        updateVisualStok();
    }
}

// ============================================
// UPDATE VISUAL STOK GUDANG
// ============================================

function updateVisualStok() {
    // Update kartu stok dashboard
    const sMentah = globalState.databaseStok['Kulit Mentah'] || 0;
    const sMinyak = globalState.databaseStok['Minyak'] || 0;
    const sGas = globalState.databaseStok['Gas'] || 0;

    document.getElementById('stokKulitMentah').innerText = sMentah;
    document.getElementById('stokMinyak').innerText = sMinyak;
    document.getElementById('stokGas').innerText = sGas;

    document.getElementById('stokKulitMentah').className = `text-lg font-black num-transition ${sMentah < 0 ? 'text-red-600' : 'text-indigo-900'}`;
    document.getElementById('stokMinyak').className = `text-lg font-black num-transition ${sMinyak < 0 ? 'text-red-600' : 'text-teal-900'}`;
    document.getElementById('stokGas').className = `text-lg font-black num-transition ${sGas < 0 ? 'text-red-600' : 'text-orange-900'}`;

    // Update akumulasi pemakaian
    document.getElementById('totalPakaiMentah').innerText = globalState.akumulasiPakai.mentah;
    document.getElementById('totalPakaiMinyak').innerText = globalState.akumulasiPakai.minyak;
    document.getElementById('totalPakaiGas').innerText = globalState.akumulasiPakai.gas;

    // Render daftar lengkap stok di modal gudang & siapkan opsi
    let htmlList = '';
    
    let opsiKategori = `
        <option value="Kulit Mentah">🥩 Kulit Mentah</option>
        <option value="Minyak">💧 Minyak Goreng</option>
        <option value="Gas">🔥 Gas Tabung</option>
        <option value="Plastik 9x16">🛍️ Plastik 9x16</option>
        <option value="Plastik 8x13">🛍️ Plastik 8x13</option>
        <option value="Plastik 35x55">🛍️ Plastik 35x55</option>
        <option value="Ziplock 20x29">🛍️ Ziplock 20x29</option>
    `;

    let defaultKategori = ['Kulit Mentah', 'Minyak', 'Gas', 'Plastik 9x16', 'Plastik 8x13', 'Plastik 35x55', 'Ziplock 20x29'];

    for (const [barang, qty] of Object.entries(globalState.databaseStok)) {
        let satuan = (barang === 'Gas') ? 'Tbg' : 'Kg';
        
        let tombolHapus = '';
        if (!['Kulit Mentah', 'Minyak', 'Gas'].includes(barang)) {
            tombolHapus = `<button type="button" onclick="hapusJenisBahanBaku('${barang}')" class="text-[9px] bg-red-50 text-red-600 border border-red-200 px-2 py-1.5 rounded-xl shadow-sm cursor-pointer btn-press hover:bg-red-100 transition-colors font-bold ml-0.5" title="Hapus Item">❌</button>`;
        }

        htmlList += `<div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-xl border border-gray-200">
            <span class="font-semibold text-gray-800">${barang}</span>
            <div class="flex items-center gap-1">
                <span class="font-black text-indigo-700 w-12 text-right mr-1">${qty} <span class="text-[9px] text-gray-400 font-normal">${satuan}</span></span>
                <button type="button" onclick="closeModal('modalGudang'); setTimeout(() => bukaFormKredit('${barang}'), 300);" class="text-[9px] bg-indigo-600 text-white px-2.5 py-1.5 rounded-xl shadow-sm cursor-pointer btn-press font-bold hover:bg-indigo-700 transition-colors">Beli</button>
                <button type="button" onclick="editManualStok('${barang}')" class="text-[9px] bg-white text-gray-600 border border-gray-300 px-2.5 py-1.5 rounded-xl shadow-sm cursor-pointer btn-press hover:bg-gray-100 transition-colors">Koreksi</button>
                ${tombolHapus}
            </div>
        </div>`;

        if (!defaultKategori.includes(barang)) {
            opsiKategori += `<option value="${barang}">📦 ${barang}</option>`;
        }
    }
    
    opsiKategori += `<option value="Lainnya">✏️ Lainnya</option>`;

    document.getElementById('listStokLengkap').innerHTML = htmlList;

    const selectKategori = document.getElementById('kreditKategori');
    if (selectKategori) {
        let currKat = selectKategori.value;
        selectKategori.innerHTML = opsiKategori;
        if(opsiKategori.includes(`"${currKat}"`)) selectKategori.value = currKat;
    }

    // Render histori gudang
    let htmlHistori = '';
    globalState.historiGudang.forEach(h => {
        htmlHistori += `<div class="border-b border-gray-100 pb-1.5 mb-1.5 text-gray-600 last:border-none">${h}</div>`;
    });
    document.getElementById('historiGudangVisual').innerHTML = htmlHistori || '<p class="text-center text-gray-400 italic py-2">Belum ada riwayat alur logistik.</p>';
}
