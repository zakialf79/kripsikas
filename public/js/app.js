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
    akumulasiPakai: { mentah: 0, minyak: 0, gas: 0 },
    revenueHistory: []
};

let arrChartsGlobal = [];

// ============================================
// INISIALISASI APLIKASI
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadSettings();
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
        globalState.revenueHistory = data.revenueHistory || [];

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
async function kirimStateKeMySQL(newLog = '', clearHistori = false) {
    try {
        const payload = {
            databaseStok: globalState.databaseStok,
            listBukuKas: globalState.listBukuKas,
            akumulasiPakai: globalState.akumulasiPakai,
            newLogTeks: newLog,
            clearHistoriGudang: clearHistori
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
    const ok = await customConfirm('Apakah Anda yakin ingin keluar dari aplikasi?', '🚪', 'Ya, Keluar');
    if (!ok) return;

    try {
        await fetch(`${API_URL}?action=logout`);
    } catch (e) {
        // Ignore error
    }
    window.location.href = 'index.php';
}

// ============================================
// PENGATURAN TAMPILAN (TEMA & TEKS)
// ============================================
function loadSettings() {
    const isDark = localStorage.getItem('krispikas_dark_mode') === 'true';
    const isLarge = localStorage.getItem('krispikas_large_text') === 'true';

    const toggleDark = document.getElementById('toggleDarkMode');
    const toggleLarge = document.getElementById('toggleLargeText');

    if (isDark) {
        document.documentElement.classList.add('dark');
        if (toggleDark) toggleDark.checked = true;
    }
    if (isLarge) {
        document.documentElement.classList.add('teks-besar');
        if (toggleLarge) toggleLarge.checked = true;
    }
}

function toggleTheme() {
    const toggle = document.getElementById('toggleDarkMode');
    if (toggle.checked) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('krispikas_dark_mode', 'true');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('krispikas_dark_mode', 'false');
    }
}

function toggleLargeText() {
    const toggle = document.getElementById('toggleLargeText');
    if (toggle.checked) {
        document.documentElement.classList.add('teks-besar');
        localStorage.setItem('krispikas_large_text', 'true');
    } else {
        document.documentElement.classList.remove('teks-besar');
        localStorage.setItem('krispikas_large_text', 'false');
    }
}
