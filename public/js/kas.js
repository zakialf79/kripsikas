/**
 * KrispiKas - Buku Kas Module
 * 
 * Mengelola catatan debet (uang masuk) dan kredit (uang keluar).
 */

// ============================================
// PILIHAN UANG MASUK
// ============================================

function openPilihanUangMasuk() {
    openModal('modalPilihanUangMasuk');
}

function populateDropdownNama(metode) {
    let list = metode === 'Konsinyasi' ? globalState.agenKonsinyasi : globalState.agenLangsung;
    let html = '';
    list.forEach(agen => {
        html += `<option value="${agen}">${agen}</option>`;
    });
    document.getElementById('debetPilihNama').innerHTML = html || `<option value="" disabled selected>Belum ada nama...</option>`;
}

function lanjutFormDebet(metode) {
    closeModal('modalPilihanUangMasuk');
    const judul = document.getElementById('judulFormDebet');
    document.getElementById('debetMetodePenjualan').value = metode;
    const bKonsinyasi = document.getElementById('blokKonsinyasi');
    const bLangsung = document.getElementById('blokBayarLangsung');
    const wOngkir = document.getElementById('wrapperDebetOngkir');

    // Reset form
    document.getElementById('debetQty').value = '';
    document.getElementById('debetHargaSatuan').value = '';
    if (document.getElementById('debetOngkir')) document.getElementById('debetOngkir').value = '';
    document.getElementById('debetTotalUangVisual').value = '';

    populateDropdownNama(metode);

    if (metode === 'Konsinyasi') {
        judul.innerText = '🤝 PENDAPATAN KONSINYASI';
        bKonsinyasi.classList.remove('hidden');
        bLangsung.classList.add('hidden');
        if (wOngkir) wOngkir.classList.add('hidden');
    } else {
        judul.innerText = '💵 PEMBAYARAN TUNAI';
        bKonsinyasi.classList.add('hidden');
        bLangsung.classList.remove('hidden');
        if (wOngkir) wOngkir.classList.remove('hidden');
        updateOpsiUnitLangsung();
    }
    openModal('modalDebet');
}

function kembaliKePilihan() {
    closeModal('modalDebet');
    openModal('modalPilihanUangMasuk');
}

function updateOpsiUnitLangsung() {
    const kondisi = document.getElementById('langsungKondisi').value;
    const unit = document.getElementById('langsungUnit');
    if (kondisi === 'Mentah') {
        unit.innerHTML = '<option value="Kg">Per Kg</option>';
    } else {
        unit.innerHTML = '<option value="Kg">Per Kg</option><option value="Pcs">Per Pcs</option><option value="Pack">Per Pack</option>';
    }
}

function hitungTotalDebet() {
    let qty = cleanNumber(document.getElementById('debetQty').value);
    let harga = cleanRupiah(document.getElementById('debetHargaSatuan').value);
    const metodePenjualan = document.getElementById('debetMetodePenjualan').value;
    let ongkir = (metodePenjualan === 'Konsinyasi') ? 0 : cleanRupiah(document.getElementById('debetOngkir') ? document.getElementById('debetOngkir').value : '0');
    let total = (qty * harga) + ongkir;
    document.getElementById('debetTotalUangVisual').value = total > 0 ? total.toLocaleString('id-ID') : '';
}

// ============================================
// SIMPAN DEBET (UANG MASUK)
// ============================================

function simpanDebet(e) {
    e.preventDefault();
    
    const tglRaw = document.getElementById('debetTanggal').value;
    if (!tglRaw) return alert('Isi Tanggal!');
    
    const d = new Date(tglRaw);
    const tglVisual = `${d.getDate()}/${d.getMonth() + 1}`;
    const metodePenjualan = document.getElementById('debetMetodePenjualan').value;
    const metodeBayar = document.getElementById('debetMetodeBayar').value;
    const nama = document.getElementById('debetPilihNama').value || 'Tanpa Nama';
    const qty = cleanNumber(document.getElementById('debetQty').value);
    const harga = cleanRupiah(document.getElementById('debetHargaSatuan').value);
    const ongkir = (metodePenjualan === 'Konsinyasi') ? 0 : cleanRupiah(document.getElementById('debetOngkir') ? document.getElementById('debetOngkir').value : '0');
    const detail = document.getElementById('debetKeteranganTambahan').value.trim();

    if (qty * harga === 0) return alert('Qty dan Harga tidak boleh kosong!');

    let tipeCetak = '';
    let stokTarget = '';

    if (metodePenjualan === 'Konsinyasi') {
        stokTarget = document.getElementById('konsinyasiVarian').value;
        tipeCetak = `${stokTarget} (Pack)`;
    } else {
        let kond = document.getElementById('langsungKondisi').value;
        let unit = document.getElementById('langsungUnit').value;
        if (kond === 'Mentah') {
            stokTarget = 'Kulit Mentah';
            tipeCetak = 'Kulit Mentah (Kg)';
        } else {
            tipeCetak = `Matang (${unit})`;
        }
    }

    let logGudangText = '';
    if (stokTarget === 'Kulit Mentah' && globalState.databaseStok[stokTarget] !== undefined && qty > 0) {
        globalState.databaseStok[stokTarget] -= qty;
        logGudangText = `📤 Jual Kulit Keluar: ${qty}Kg ke ${nama}`;
    }

    let tag = metodePenjualan === 'Konsinyasi' ? '[Titip]' : '[Tunai]';
    let ketFinal = `${tag} ${nama} - ${qty} ${tipeCetak} [${metodeBayar}]`;
    if (ongkir > 0) ketFinal += ` (+ Ongkir: Rp ${ongkir.toLocaleString('id-ID')})`;
    if (detail) ketFinal += ` (${detail})`;

    let totalDebet = (qty * harga) + ongkir;

    globalState.listBukuKas.push({
        id: Date.now(),
        tglSort: tglRaw,
        tgl: tglVisual,
        ket: ketFinal,
        debet: totalDebet,
        kredit: 0
    });

    closeModal('modalDebet');
    kirimStateKeMySQL(logGudangText);
}

// ============================================
// SIMPAN KREDIT (UANG KELUAR)
// ============================================

/**
 * Toggle tampilan dropdown ukuran plastik.
 * FIX: Fungsi ini sebelumnya hilang dari kode lama.
 */
function gantiKategoriKredit() {
    const kat = document.getElementById('kreditKategori').value;
    const wrapper = document.getElementById('wrapperUkuranPlastik');
    if (kat === 'Plastik') {
        wrapper.classList.remove('hidden');
    } else {
        wrapper.classList.add('hidden');
    }
}

function togglePlastikCustom() {
    const val = document.getElementById('kreditUkuranPlastik').value;
    const wrapper = document.getElementById('wrapperPlastikCustom');
    if (val === '__custom__') {
        wrapper.classList.remove('hidden');
    } else {
        wrapper.classList.add('hidden');
    }
}

function simpanKredit(e) {
    e.preventDefault();
    
    const tglRaw = document.getElementById('kreditTanggal').value;
    if (!tglRaw) return alert('Isi Tanggal!');
    
    const d = new Date(tglRaw);
    const tglVisual = `${d.getDate()}/${d.getMonth() + 1}`;
    const kat = document.getElementById('kreditKategori').value;
    const detail = document.getElementById('kreditKeteranganTambahan').value.trim();
    const vol = cleanNumber(document.getElementById('kreditVolumeBeli').value);
    const satuan = document.getElementById('kreditSatuanBeli').value;
    const nominal = cleanRupiah(document.getElementById('kreditJumlahUang').value);

    if (nominal === 0) return alert('Nominal tidak boleh kosong!');

    let namaBrg = kat === 'Plastik' ? document.getElementById('kreditUkuranPlastik').value : kat;
    if (namaBrg === '__custom__') {
        namaBrg = document.getElementById('kreditPlastikCustom').value.trim();
        if (!namaBrg) return alert('Nama plastik baru wajib diisi!');
    }
    
    let logGudangText = '';

    if (kat !== 'Lainnya' && vol > 0) {
        if (globalState.databaseStok[namaBrg] === undefined) {
            globalState.databaseStok[namaBrg] = 0; // Initialize new custom plastic
        }
        globalState.databaseStok[namaBrg] += vol;
        logGudangText = `📥 Masuk Bahan Belanja: ${namaBrg} +${vol}${satuan}`;
    }

    let ketFinal = kat === 'Lainnya' ? detail : `Beli ${namaBrg} ${vol}${satuan}`;
    if (kat !== 'Lainnya' && detail) ketFinal += ` (${detail})`;

    globalState.listBukuKas.push({
        id: Date.now(),
        tglSort: tglRaw,
        tgl: tglVisual,
        ket: ketFinal,
        debet: 0,
        kredit: nominal
    });

    closeModal('modalKredit');
    kirimStateKeMySQL(logGudangText);
}

// ============================================
// TABEL BUKU KAS
// ============================================

function updateTabelKas() {
    const tbody = document.getElementById('bodyTabelKas');
    let html = '';
    let runningSaldo = 0;

    globalState.listBukuKas.forEach(item => {
        let dbt = parseInt(item.debet);
        let krd = parseInt(item.kredit);
        runningSaldo = runningSaldo + dbt - krd;

        let cDebet = dbt > 0 ? dbt.toLocaleString('id-ID') : '';
        let cKredit = krd > 0 ? krd.toLocaleString('id-ID') : '';
        let rowBg = item.ket.toLowerCase().includes('sisa saldo') ? 'bg-amber-50/70' : 'bg-white';

        html += `<tr class="border-b border-gray-100 ${rowBg} table-row-hover">
            <td class="p-3 border-r border-gray-100 text-center font-bold text-gray-500">${item.tgl}</td>
            <td class="p-3 border-r border-gray-100 font-bold text-gray-800 break-words max-w-[130px]">${item.ket}</td>
            <td class="p-3 border-r border-gray-100 text-right text-green-600 font-extrabold">${cDebet}</td>
            <td class="p-3 border-r border-gray-100 text-right text-red-600 font-extrabold">${cKredit}</td>
            <td class="p-3 text-right font-black text-slate-900 bg-amber-50/20">${runningSaldo.toLocaleString('id-ID')}</td>
            <td class="p-2 text-center id-anti-cetak">
                <button onclick="hapusSatuBaris(${item.id})" class="text-red-500 font-bold p-1 bg-red-50 hover:bg-red-100 rounded-xl transition-colors cursor-pointer">✕</button>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html || '<tr><td colspan="6" class="text-center py-6 text-gray-400 font-bold">Buku kas kosong.</td></tr>';
    document.getElementById('dashSaldoAkhir').innerText = 'Rp ' + runningSaldo.toLocaleString('id-ID');
}

async function hapusSatuBaris(id) {
    const ok = await customConfirm('Hapus baris catatan kas ini?', '🗑️', 'Ya, Hapus');
    if (ok) {
        globalState.listBukuKas = globalState.listBukuKas.filter(i => i.id !== id);
        kirimStateKeMySQL();
    }
}

async function resetBukuKas() {
    const ok = await customConfirm('KOSONGKAN TOTAL seluruh tabel kas berjalan? Semua data catatan bulan ini akan dihapus!', '⚠️', 'Ya, Kosongkan');
    if (ok) {
        globalState.listBukuKas = [];
        kirimStateKeMySQL();
    }
}

// ============================================
// TUTUP BUKU BULANAN (ARSIP)
// ============================================

async function tutupBukuBulanan() {
    let saldo = 0;
    globalState.listBukuKas.forEach(i => saldo += (parseInt(i.debet) - parseInt(i.kredit)));

    const ok = await customConfirm(
        `TUTUP BUKU BULAN INI?\nSaldo akhir: Rp ${saldo.toLocaleString('id-ID')}\n\nData akan diarsipkan dan tidak bisa diubah lagi.`,
        '🔒',
        'Ya, Tutup Buku'
    );

    if (!ok) return;

    const namaBulan = await customPrompt('Ketik nama bulan (Contoh: Juni 2026):');
    if (!namaBulan) return;

    globalState.listBukuKas.forEach(row => {
        row.is_arsip = 1;
        row.nama_bulan_arsip = namaBulan;
    });

    const now = new Date();
    const tglVisualAk = `${now.getDate()}/${now.getMonth() + 1}`;

    let sisaRow = {
        id: Date.now(),
        tglSort: now.toISOString().split('T')[0],
        tgl: tglVisualAk,
        ket: `Sisa saldo ${namaBulan}`,
        debet: saldo,
        kredit: 0
    };

    globalState.listBukuKas = [sisaRow];
    globalState.akumulasiPakai = { mentah: 0, minyak: 0, gas: 0 };

    kirimStateKeMySQL(`🔒 Sistem: Tutup buku dan pengarsipan untuk periode ${namaBulan}`);
}
