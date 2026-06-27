/**
 * KrispiKas - Main Application
 * 
 * Inisialisasi state global, load data dari server, dan sinkronisasi.
 */

const API_URL = 'index.php';

// ============================================
// GLOBAL STATE
// ============================================
let globalState = {
    listBukuKas: [],
    databaseStok: {},
    agenKonsinyasi: [],
    agenLangsung: [],
    historiGudang: [],
    arsipBulanList: [],
    akumulasiPakai: { mentah: 0, minyak: 0, gas: 0 }
};

let arrChartsGlobal = [];

// ============================================
// INISIALISASI APLIKASI
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadDataDariMySQL();
});

// ============================================
// LOAD DATA DARI SERVER (AJAX)
// ============================================
async function loadDataDariMySQL() {
    try {
        const res = await fetch(`${API_URL}?action=get_init_data`);
        
        if (res.status === 401) {
            // Session expired, redirect ke login
            window.location.href = 'index.php';
            return;
        }

        const data = await res.json();

        if (data.error) {
            showToast('Gagal memuat data: ' + data.error, '❌', 4000);
            return;
        }

        globalState.listBukuKas    = data.listBukuKas || [];
        globalState.databaseStok   = data.databaseStok || {};
        globalState.agenKonsinyasi = data.agenKonsinyasi || [];
        globalState.agenLangsung   = data.agenLangsung || [];
        globalState.historiGudang  = data.historiGudang || [];
        globalState.arsipBulanList = data.arsipBulanList || [];
        globalState.akumulasiPakai = data.akumulasiPakai || { mentah: 0, minyak: 0, gas: 0 };

        // Update semua visual
        setTanggalInputDefault();
        renderKelolaAgen();
        updateTabelKas();
        updateVisualStok();

    } catch (err) {
        showToast('Gagal terhubung ke server! Pastikan XAMPP aktif.', '❌', 5000);
        console.error('Load data error:', err);
    }
}

// ============================================
// KIRIM SINKRONISASI STATE KE SERVER
// ============================================
async function kirimStateKeMySQL(newLog = '') {
    try {
        const payload = {
            databaseStok: globalState.databaseStok,
            listBukuKas: globalState.listBukuKas,
            akumulasiPakai: globalState.akumulasiPakai,
            newLogTeks: newLog
        };

        const res = await fetch(`${API_URL}?action=sync_all_state`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (result.status === 'success') {
            showToast('Data berhasil disimpan!', '✅');
        } else {
            showToast('Gagal menyimpan: ' + (result.message || 'Unknown error'), '❌', 4000);
        }

        // Reload data terbaru dari server
        loadDataDariMySQL();

    } catch (err) {
        showToast('Gagal sinkronisasi ke server!', '❌', 4000);
        console.error('Sync error:', err);
    }
}

// ============================================
// UTILITY: SET TANGGAL DEFAULT
// ============================================
function setTanggalInputDefault() {
    const hariIni = new Date().toISOString().split('T')[0];
    
    const debetTgl = document.getElementById('debetTanggal');
    const kreditTgl = document.getElementById('kreditTanggal');
    const visualBulan = document.getElementById('visualBulan');
    
    if (debetTgl) debetTgl.value = hariIni;
    if (kreditTgl) kreditTgl.value = hariIni;
    if (visualBulan) {
        visualBulan.innerText = 'Catatan Hari Ini: ' + new Date().toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });
    }
}

// ============================================
// LOGOUT
// ============================================
async function prosesLogout() {
    try {
        await fetch(`${API_URL}?action=logout`);
    } catch (e) {
        // Ignore error
    }
    window.location.href = 'index.php';
}
