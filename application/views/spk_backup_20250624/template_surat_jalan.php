<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MPM Site | Surat Jalan</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #000;
            background: white;
        }

        .page-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 0;
            background: white;
        }

        /* Header Section */
        .header-section {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-logo {
            width: 100px;
            height: auto;
        }

        .document-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .document-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* Info Table */
        .info-section {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 5px;
            font-size: 11px;
            vertical-align: top;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-colon {
            width: 10px;
        }

        .info-value {
            font-weight: normal;
        }

        .info-highlight {
            font-weight: bold;
        }

        /* Product Table */
        .product-section {
            width: 100%;
            margin: 15px 0;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            font-size: 10px;
        }

        .product-table th {
            background-color: #f5f5f5;
            border: 1px solid #000;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            line-height: 1.2;
        }

        .product-table td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
            line-height: 1.2;
        }

        .product-table .text-left {
            text-align: left;
        }

        .product-table .text-right {
            text-align: right;
        }

        .total-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            font-size: 10px;
        }

        /* Column Widths */
        .col-kode { width: 8%; }
        .col-prc { width: 10%; }
        .col-nama { width: 32%; }
        .col-batch { width: 10%; }
        .col-ed { width: 10%; }
        .col-unit { width: 10%; }
        .col-karton { width: 10%; }
        .col-berat { width: 15%; }
        .col-volume { width: 15%; }

        /* Signature Section */
        .signature-section {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .signature-table th {
            border: 1px solid #000;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            background-color: #f5f5f5;
        }

        .signature-table td {
            border: 1px solid #000;
            padding: 15px 8px;
            text-align: center;
            height: 80px;
            vertical-align: bottom;
            font-size: 10px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 9px;
            color: #666;
            font-style: italic;
        }

        .signature-image {
            max-width: 100px;
            max-height: 80px;
            margin-bottom: 5px;
        }

        /* Footer */
        .footer-section {
            width: 100%;
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }

        /* Print Specific */
        @media print {
            body { 
                font-size: 10px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .page-container { 
                max-width: none;
                margin: 0;
                padding: 0;
            }
        }

        /* Row striping */
        .product-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-flex">
                <div>
                    <img src="./assets/css/images/mpm_new.jpg" class="company-logo" alt="PT. MPM Logo">
                </div>
                <div class="document-info">
                    Dokumen No: <?= $kode_surat_jalan; ?><br>
                    Tanggal: <?= date('d F Y', strtotime($created_at)); ?>
                </div>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">SURAT JALAN</div>

        <!-- Information Section -->
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="info-label">No. Surat Jalan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value info-highlight"><?= $kode_surat_jalan; ?></td>
                    <td style="width: 50px;"></td>
                    <td class="info-label">Tanggal</td>
                    <td class="info-colon">:</td>
                    <td class="info-value"><?= date('d F Y', strtotime($created_at)); ?></td>
                </tr>
                <tr>
                    <td class="info-label">Pemesan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value info-highlight" colspan="5"><?= strtoupper($company); ?></td>
                </tr>
                <tr>
                    <td class="info-label">Alamat Tujuan</td>
                    <td class="info-colon">:</td>
                    <td class="info-value" colspan="5"><?= $alamat_gudang; ?></td>
                </tr>
                <tr>
                    <td class="info-label">Referensi PO</td>
                    <td class="info-colon">:</td>
                    <td class="info-value" colspan="5"><?= $nopo; ?> (<?= date('d/m/Y', strtotime($tglpo)); ?>)</td>
                </tr>
                <tr>
                    <td class="info-label">Referensi DO</td>
                    <td class="info-colon">:</td>
                    <td class="info-value" colspan="5"><?= $nodo; ?> (<?= date('d/m/Y', strtotime($tgldo)); ?>)</td>
                </tr>
            </table>
        </div>

        <!-- Product Table -->
        <div class="product-section">
            <table class="product-table">
                <thead>
                    <tr>
                        <th class="col-kode">Kode</th>
                        <th class="col-prc">Kode PRC</th>
                        <th class="col-nama">Nama Produk</th>
                        <th class="col-batch">Batch Number</th>
                        <th class="col-ed">ED</th>
                        <th class="col-unit">Unit</th>
                        <th class="col-karton">Karton</th>
                        <th class="col-berat">Berat (Kg)</th>
                        <th class="col-volume">Volume (m³)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_karton_calc = 0;
                    $total_berat_calc = 0;
                    $total_volume_calc = 0;
                    
                    foreach ($get_detail->result() as $key) { 
                        // Sanitize and convert numeric values to prevent PHP errors
                        $karton = (isset($key->total_karton) && is_numeric($key->total_karton)) ? (float)$key->total_karton : 0;
                        $berat = (isset($key->total_karton_berat) && is_numeric($key->total_karton_berat)) ? (float)$key->total_karton_berat : 0;
                        $volume = (isset($key->total_karton_volume) && is_numeric($key->total_karton_volume)) ? (float)$key->total_karton_volume : 0;
                        $unit = (isset($key->banyak) && is_numeric($key->banyak)) ? (float)$key->banyak : 0;
                        
                        // Calculate totals
                        $total_karton_calc += $karton;
                        $total_berat_calc += $berat;
                        $total_volume_calc += $volume;
                    ?>
                        <tr>
                            <td class="text-left"><?= htmlspecialchars(isset($key->kodeprod) ? $key->kodeprod : ''); ?></td>
                            <td class="text-left"><?= htmlspecialchars(isset($key->kode_prc) ? $key->kode_prc : ''); ?></td>
                            <td class="text-left"><?= htmlspecialchars(isset($key->namaprod) ? $key->namaprod : ''); ?></td>
                            <td class="text-left"><?= htmlspecialchars(isset($key->batch_number) ? $key->batch_number : ''); ?></td>
                            <td class="text-left"><?= htmlspecialchars(isset($key->ed) ? $key->ed : ''); ?></td>
                            <td class="text-right"><?= number_format($unit, 0, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format($karton, 0, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format($berat, 2, ',', '.'); ?></td>
                            <td class="text-right"><?= number_format($volume, 3, ',', '.'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td class="text-left" colspan="6"><strong>TOTAL</strong></td>
                        <td class="text-right"><strong><?= number_format($total_karton_calc, 0, ',', '.'); ?></strong></td>
                        <td class="text-right"><strong><?= number_format($total_berat_calc, 2, ',', '.'); ?></strong></td>
                        <td class="text-right"><strong><?= number_format($total_volume_calc, 3, ',', '.'); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <table class="signature-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Diserahkan Oleh</th>
                        <th style="width: 25%;">Dikirim Oleh</th>
                        <th style="width: 25%;">Diterima Oleh</th>
                        <th style="width: 25%;">Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php if(file_exists('./assets/css/images/ttd_p_fakhrul_stempel.jpg')): ?>
                                <img src="./assets/uploads/signature/melinda-signature.png" alt="Signature" class="signature-image">
                            <?php endif; ?>
                            <div class="signature-name">Melinda</div>
                        </td>
                        <td>
                            <div class="signature-name"><?= htmlspecialchars(isset($vendor) ? $vendor : 'Vendor'); ?></div>
                            <!-- <div class="signature-title"></div> -->
                        </td>
                        <td>
                            <div class="signature-name">DP</div>
                            <!-- <div class="signature-title"></div> -->
                        </td>
                        <td>
                            <?php if(file_exists('./assets/css/images/ttd_p_fakhrul_stempel.jpg')): ?>
                                <img src="./assets/css/images/ttd_p_fakhrul_stempel.jpg" alt="Signature" class="signature-image">
                            <?php endif; ?>
                            <div class="signature-name">Fakhrul Hidayat</div>
                            <!-- <div class="signature-title"></div> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <div>Dokumen ini dicetak secara otomatis pada <?= date('d F Y H:i:s'); ?></div>
            <div>PT. MPM - Surat Jalan Pengiriman Barang</div>
        </div>
    </div>

    <!-- Print Button -->
    <!-- <div style="text-align: center; margin: 20px 0;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            📄 Cetak Dokumen
        </button>
    </div> -->

    <script>
        // Fix for numeric formatting issues
        document.addEventListener('DOMContentLoaded', function() {
            // Auto format numbers on load if needed
            const numberCells = document.querySelectorAll('.text-right');
            numberCells.forEach(cell => {
                const text = cell.textContent.trim();
                if (text && !isNaN(text.replace(/[,\.]/g, ''))) {
                    // Already formatted by PHP, no need to reformat
                }
            });
        });
        
        // Print function
        function printDocument() {
            window.print();
        }
    </script>
</body>

</html>