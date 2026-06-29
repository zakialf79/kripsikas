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
    startClock();
    loadDataDariMySQL();
});

// ============================================
// JAM DIGITAL (GMT+7)
// ============================================
function startClock() {
    const clockEl = document.getElementById('liveClock');
    if (!clockEl) return;

    // Update setiap detik
    setInterval(() => {
        const now = new Date();
        const timeString = new Intl.DateTimeFormat('id-ID', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        }).format(now);
        
        // Memastikan format dipisahkan tanda titik dua, karena terkadang browser beda format locale
        clockEl.innerText = timeString.replace(/\./g, ':'); 
    }, 1000);
}

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

        setTanggalInputDefault();
        renderKelolaAgen();
        
        // Cek auto tutup buku sebelum update visual
        const autoTutupBerjalan = cekAutoTutupBuku();
        
        if (!autoTutupBerjalan) {
            updateTabelKas();
            updateVisualStok();
            cekPengingatHarian();
        }

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
// PENGINGAT HARIAN
// ============================================
function cekPengingatHarian() {
    const today = new Date().toISOString().split('T')[0];
    const lastReminder = localStorage.getItem('krispikas_last_reminder');
    
    if (lastReminder === today) return; // Sudah diingatkan hari ini

    const adaCatatanHariIni = globalState.listBukuKas.some(row => row.tglSort === today);
    
    if (!adaCatatanHariIni) {
        setTimeout(async () => {
            await customAlert('Halo Ibu! ☀️ Sepertinya belum ada pencatatan kas atau gudang hari ini. Jangan lupa dicatat ya!', '🔔', 'Oke, Siap!');
            localStorage.setItem('krispikas_last_reminder', today);
        }, 1500); // Muncul 1.5 detik setelah web dimuat
    }
}

// ============================================
// AUTO-TUTUP BUKU (PERGANTIAN BULAN)
// ============================================
function cekAutoTutupBuku() {
    if (!globalState.listBukuKas || globalState.listBukuKas.length === 0) return false;

    const now = new Date();
    const currTahun = now.getFullYear();
    const currBulan = now.getMonth() + 1; // 1-12

    // Karena diurutkan ASC dari backend, array ke-0 adalah catatan tertua yang aktif
    const catatanTertua = globalState.listBukuKas[0];
    const tglUtama = new Date(catatanTertua.tglSort);
    const thnTertua = tglUtama.getFullYear();
    const blnTertua = tglUtama.getMonth() + 1;

    // Pastikan valid dan berasal dari bulan/tahun sebelumnya
    if (!isNaN(thnTertua) && (thnTertua < currTahun || (thnTertua === currTahun && blnTertua < currBulan))) {
        
        let saldo = 0;
        globalState.listBukuKas.forEach(i => saldo += (parseInt(i.debet) - parseInt(i.kredit)));

        const namaBulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const namaBulanArsip = `${namaBulanIndo[blnTertua - 1]} ${thnTertua}`;

        let barisArsip = [];
        globalState.listBukuKas.forEach(row => {
            row.is_arsip = 1;
            row.nama_bulan_arsip = namaBulanArsip;
            barisArsip.push(row);
        });

        const tglVisualAk = `${now.getDate()}/${now.getMonth() + 1}`;
        let sisaRow = {
            id: Date.now(),
            tglSort: now.toISOString().split('T')[0],
            tgl: tglVisualAk,
            ket: `💰 Sisa saldo ${namaBulanArsip}`,
            debet: saldo > 0 ? saldo : 0,
            kredit: saldo < 0 ? Math.abs(saldo) : 0
        };

        let payloadTutupBuku = [...barisArsip, sisaRow];
        
        globalState.listBukuKas = payloadTutupBuku;
        globalState.akumulasiPakai = { mentah: 0, minyak: 0, gas: 0 };
        globalState.historiGudang = [];

        kirimStateKeMySQL(`🔒 Sistem: Auto-Tutup buku bulan ${namaBulanArsip} selesai.`, true);
        
        setTimeout(() => {
            if (typeof customAlert === 'function') {
                customAlert(`Halo Ibu! Sistem otomatis melakukan Tutup Buku untuk ${namaBulanArsip} karena sudah memasuki bulan baru. Saldo kas sudah dipindahkan ke tabel hari ini.`, '🤖', 'Terima Kasih');
            }
        }, 1500);

        return true;
    }
    return false;
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
