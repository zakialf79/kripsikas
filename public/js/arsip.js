/**
 * KrispiKas - Arsip & Download Module
 * 
 * Mengelola arsip bulanan, export CSV dan PDF.
 */

// ============================================
// BUKA MODAL ARSIP
// ============================================

function bukaArsipBulanan() {
    let html = '';
    globalState.arsipBulanList.forEach(bulan => {
        html += `<div class="bg-purple-50 border border-purple-100 p-3 rounded-2xl flex flex-col gap-2 shadow-sm">
            <span class="font-black text-purple-900 text-xs">${bulan}</span>
            <div class="grid grid-cols-2 gap-1.5">
                <button onclick="downloadCSVData('${bulan}')" class="bg-emerald-600 text-white px-2 py-1.5 rounded-xl text-[9px] font-bold shadow text-center cursor-pointer btn-press">Excel (CSV)</button>
                <button onclick="downloadPDFData('${bulan}')" class="bg-red-600 text-white px-2 py-1.5 rounded-xl text-[9px] font-bold shadow text-center cursor-pointer btn-press">Dokumen PDF</button>
            </div>
        </div>`;
    });
    document.getElementById('listArsipVisual').innerHTML = html || '<p class="text-center text-gray-400 italic py-2">Belum ada arsip tersimpan.</p>';
    openModal('modalArsip');
}

// ============================================
// DOWNLOAD CSV
// ============================================

function downloadCSVBulanIni() {
    generateCSV(globalState.listBukuKas, 'Buku_Kas_Bulan_Berjalan');
}

async function downloadCSVData(bulanKunci) {
    try {
        const res = await fetch(`${API_URL}?action=get_arsip_bulan&bulan=${encodeURIComponent(bulanKunci)}`);
        const dataArsip = await res.json();
        generateCSV(dataArsip, `Arsip_Kas_${bulanKunci}`);
    } catch (err) {
        showToast('Gagal mengambil data arsip!', '❌');
    }
}

function generateCSV(dataArray, filename) {
    let csvContent = 'data:text/csv;charset=utf-8,Tanggal,Keterangan,Debet (Masuk),Kredit (Keluar)\n';
    dataArray.forEach(row => {
        csvContent += `"${row.tgl}","${row.ket}",${row.debet},${row.kredit}\n`;
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', filename + '.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showToast(`${filename}.csv berhasil didownload!`, '📊');
}

// ============================================
// DOWNLOAD PDF
// ============================================

function downloadPDFBulanIni() {
    const areaCetak = document.getElementById('areaCetakBukuKas');
    const opsi = {
        margin: [10, 10, 10, 10],
        filename: 'Buku_Kas_Bulan_Berjalan.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, ignoreElements: (el) => el.classList.contains('id-anti-cetak') },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    areaCetak.classList.add('p-8');
    html2pdf().set(opsi).from(areaCetak).save().then(() => {
        areaCetak.classList.remove('p-8');
        showToast('PDF berhasil didownload!', '📄');
    });
}

async function downloadPDFData(bulanKunci) {
    try {
        const res = await fetch(`${API_URL}?action=get_arsip_bulan&bulan=${encodeURIComponent(bulanKunci)}`);
        const targetData = await res.json();

        let divBayangan = document.createElement('div');
        divBayangan.style.padding = '20px';
        divBayangan.style.fontFamily = 'sans-serif';

        let htmlContent = `
            <div style="text-align:center; padding-bottom:15px; border-b:2px solid #000; margin-bottom:15px;">
                <h2 style="margin:0; text-transform:uppercase;">Laporan Buku Kas Bulan ${bulanKunci}</h2>
                <p style="margin:4px 0 0 0; font-size:12px; color:#555;">Arsip Data Finansial — KrispiKas UMKM Digital</p>
            </div>
            <table style="width:100%; border-collapse:collapse; font-size:11px;">
                <thead>
                    <tr style="background-color:#fef3c7; border-bottom:1px solid #d97706; text-align:left;">
                        <th style="padding:8px; border:1px solid #ddd;">Tgl</th>
                        <th style="padding:8px; border:1px solid #ddd;">Keterangan</th>
                        <th style="padding:8px; border:1px solid #ddd; text-align:right;">Debet (Rp)</th>
                        <th style="padding:8px; border:1px solid #ddd; text-align:right;">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody>`;

        targetData.forEach(row => {
            htmlContent += `<tr style="border-bottom:1px solid #eee;">
                <td style="padding:7px; border:1px solid #ddd; text-align:center;">${row.tgl}</td>
                <td style="padding:7px; border:1px solid #ddd; font-weight:bold;">${row.ket}</td>
                <td style="padding:7px; border:1px solid #ddd; text-align:right; color:green;">${parseInt(row.debet) > 0 ? parseInt(row.debet).toLocaleString('id-ID') : ''}</td>
                <td style="padding:7px; border:1px solid #ddd; text-align:right; color:red;">${parseInt(row.kredit) > 0 ? parseInt(row.kredit).toLocaleString('id-ID') : ''}</td>
            </tr>`;
        });

        htmlContent += '</tbody></table>';
        divBayangan.innerHTML = htmlContent;

        const opsi = {
            margin: 10,
            filename: `Arsip_Kas_${bulanKunci}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opsi).from(divBayangan).save();
        showToast(`PDF arsip ${bulanKunci} berhasil didownload!`, '📄');
    } catch (err) {
        showToast('Gagal membuat PDF arsip!', '❌');
    }
}
