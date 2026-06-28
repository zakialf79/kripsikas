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
    let csvRows = [];
    csvRows.push(['Tanggal', 'Keterangan', 'Debet (Masuk)', 'Kredit (Keluar)', 'Saldo']);

    let runningSaldo = 0;
    let totalDebet = 0;
    let totalKredit = 0;

    dataArray.forEach((row, index) => {
        let debet = parseFloat(row.debet) || 0;
        let kredit = parseFloat(row.kredit) || 0;
        runningSaldo += (debet - kredit);
        totalDebet += debet;
        totalKredit += kredit;

        let rowIndex = index + 2; // Baris 1 adalah header, data mulai dari baris 2
        let formulaSaldo = (rowIndex === 2) 
            ? `"=C2-D2"` 
            : `"=E${rowIndex - 1}+C${rowIndex}-D${rowIndex}"`;

        csvRows.push([
            `"${row.tgl || ''}"`,
            `"${(row.ket || '').replace(/"/g, '""')}"`,
            debet,
            kredit,
            formulaSaldo
        ]);
    });

    let totalRowIndex = dataArray.length + 2;
    csvRows.push([
        '""', 
        '"TOTAL"', 
        `"=SUM(C2:C${totalRowIndex - 1})"`, 
        `"=SUM(D2:D${totalRowIndex - 1})"`, 
        `"=E${totalRowIndex - 1}"`
    ]);

    let csvString = '\uFEFF' + csvRows.map(e => e.join(';')).join('\r\n');
    let blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
    let url = URL.createObjectURL(blob);
    let link = document.createElement('a');
    link.setAttribute('href', url);
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
    window.print();
}

async function downloadPDFData(bulanKunci) {
    try {
        const res = await fetch(`${API_URL}?action=get_arsip_bulan&bulan=${encodeURIComponent(bulanKunci)}`);
        const targetData = await res.json();

        let htmlContent = `
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <title>Arsip ${bulanKunci}</title>
                <style>
                    body { font-family: sans-serif; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
                    th, td { border: 1px solid #ddd; padding: 8px; }
                    th { background-color: #eff6ff; text-align: left; }
                    h2 { color: #0B4CA1; margin: 0; text-transform: uppercase; }
                    p { color: #555; font-size: 12px; margin-top: 5px; }
                </style>
            </head>
            <body onload="setTimeout(() => { window.print(); window.close(); }, 500);">
                <div style="text-align:center; padding-bottom:15px; border-bottom:2px solid #000;">
                    <h2>Laporan Buku Kas Bulan ${bulanKunci}</h2>
                    <p>SIGMA — Sistem Integrasi Gudang dan Manajemen Keuangan</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>Keterangan</th>
                            <th style="text-align:right;">Debet (Rp)</th>
                            <th style="text-align:right;">Kredit (Rp)</th>
                            <th style="text-align:right;">Saldo (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>`;

        let runningSaldo = 0;
        let totalDebet = 0;
        let totalKredit = 0;
        targetData.forEach(row => {
            let dbt = parseInt(row.debet) || 0;
            let krd = parseInt(row.kredit) || 0;
            runningSaldo += (dbt - krd);
            totalDebet += dbt;
            totalKredit += krd;
            htmlContent += `<tr>
                <td style="text-align:center;">${row.tgl}</td>
                <td><strong>${row.ket}</strong></td>
                <td style="text-align:right; color:green;">${dbt > 0 ? dbt.toLocaleString('id-ID') : ''}</td>
                <td style="text-align:right; color:red;">${krd > 0 ? krd.toLocaleString('id-ID') : ''}</td>
                <td style="text-align:right; font-weight:bold;">${runningSaldo.toLocaleString('id-ID')}</td>
            </tr>`;
        });
        
        htmlContent += `<tr style="background-color:#eff6ff; font-weight:bold;">
            <td colspan="2" style="text-align:right;">TOTAL:</td>
            <td style="text-align:right; color:green;">${totalDebet.toLocaleString('id-ID')}</td>
            <td style="text-align:right; color:red;">${totalKredit.toLocaleString('id-ID')}</td>
            <td style="text-align:right;">${runningSaldo.toLocaleString('id-ID')}</td>
        </tr>`;

        htmlContent += '</tbody></table></body></html>';

        let printWin = window.open('', '_blank');
        if (printWin) {
            printWin.document.open();
            printWin.document.write(htmlContent);
            printWin.document.close();
        } else {
            showToast('Pop-up terblokir! Izinkan pop-up untuk cetak PDF.', '⚠️', 4000);
        }
    } catch (err) {
        showToast('Gagal membuat PDF arsip!', '❌');
    }
}
