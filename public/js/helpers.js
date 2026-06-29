/**
 * KrispiKas - Helper Utilities
 * 
 * Fungsi-fungsi utilitas yang dipakai di seluruh aplikasi.
 */

// ============================================
// MODAL MANAGEMENT
// ============================================

function openModal(id) {
    const mod = document.getElementById(id);
    mod.classList.replace('hidden', 'flex');
    mod.classList.add('entering');
    document.body.style.overflow = 'hidden';
    
    // Hapus class entering setelah animasi selesai
    setTimeout(() => mod.classList.remove('entering'), 300);
}

function closeModal(id) {
    const mod = document.getElementById(id);
    mod.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
}

// ============================================
// FORMAT & PARSING
// ============================================

/**
 * Format input field ke format Rupiah (titik sebagai separator ribuan).
 */
function formatInputRupiah(input) {
    let val = input.value.replace(/[^0-9]/g, '');
    input.value = val !== '' ? parseInt(val).toLocaleString('id-ID') : '';
}

/**
 * Parse string angka biasa ke float.
 */
function cleanNumber(str) {
    return parseFloat(str) || 0;
}

/**
 * Parse string format Rupiah (dengan titik/koma) ke integer.
 */
function cleanRupiah(str) {
    return parseInt(str.replace(/[^0-9]/g, '')) || 0;
}

// ============================================
// TOAST NOTIFICATION
// ============================================

/**
 * Tampilkan toast notification.
 * @param {string} message - Pesan yang ditampilkan
 * @param {string} icon - Emoji icon (default: ✅)
 * @param {number} duration - Durasi tampil dalam ms (default: 5000)
 */
function showToast(message, icon = '✅', duration = 5000) {
    const toast = document.getElementById('toastContainer');
    const toastMsg = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    if (!toast) return;
    
    toastIcon.textContent = icon;
    toastMsg.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, duration);
}

// ============================================
// CUSTOM CONFIRM & PROMPT (Menggantikan browser native)
// ============================================

let _confirmResolve = null;
let _promptResolve = null;

/**
 * Custom confirm dialog — menggantikan confirm() bawaan browser.
 * Mengembalikan Promise<boolean>.
 * 
 * @param {string} message - Pesan konfirmasi
 * @param {string} icon - Emoji icon (default: ⚠️)
 * @param {string} okText - Teks tombol OK (default: Ya, Lanjut)
 */
function customConfirm(message, icon = '⚠️', okText = 'Ya, Lanjut') {
    return new Promise((resolve) => {
        _confirmResolve = resolve;
        document.getElementById('confirmIcon').textContent = icon;
        document.getElementById('confirmMessage').textContent = message;
        document.getElementById('confirmBtnOk').textContent = okText;
        
        const modal = document.getElementById('modalConfirm');
        modal.classList.replace('hidden', 'flex');
        modal.classList.add('entering');
        document.body.style.overflow = 'hidden';
        setTimeout(() => modal.classList.remove('entering'), 300);
    });
}

function resolveConfirm(value) {
    const modal = document.getElementById('modalConfirm');
    modal.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
    if (_confirmResolve) {
        _confirmResolve(value);
        _confirmResolve = null;
    }
}

/**
 * Custom prompt dialog — menggantikan prompt() bawaan browser.
 * Mengembalikan Promise<string|null>.
 * 
 * @param {string} message - Pesan prompt
 * @param {string} defaultValue - Nilai default di input
 */
function customPrompt(message, defaultValue = '') {
    return new Promise((resolve) => {
        _promptResolve = resolve;
        document.getElementById('promptMessage').textContent = message;
        const input = document.getElementById('promptInput');
        input.value = defaultValue;
        
        const modal = document.getElementById('modalPrompt');
        modal.classList.replace('hidden', 'flex');
        modal.classList.add('entering');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            modal.classList.remove('entering');
            input.focus();
        }, 300);
    });
}

function resolvePrompt(value) {
    const modal = document.getElementById('modalPrompt');
    modal.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
    if (_promptResolve) {
        _promptResolve(value);
        _promptResolve = null;
    }
}
