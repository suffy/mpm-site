<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MPM Site | GT Market Audit</title>
    <style>
        @page {
            margin: 15mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #000;
            background: white;
        }

        .page-container {
            width: 100%;
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
            width: 50px;
            height: auto;
        }

        .document-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            letter-spacing: 3px;
            text-transform: uppercase;
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
            font-size: 9px;
            table-layout: fixed;
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
            text-align: left;
            font-size: 9px;
            line-height: 1.2;
            word-wrap: break-word;
            vertical-align: top;
        }

        .product-table .text-left {
            text-align: left;
        }

        .product-table .text-center {
            text-align: center;
        }

        /* Column Widths */
        .col-username { width: 15%; }
        .col-pasar { width: 20%; }
        .col-toko { width: 25%; }
        .col-foto { width: 40%; }

        /* Image styling */
        .foto-toko {
            max-width: 150px;
            max-height: 200px;
            display: block;
            /* margin: 0 auto; */
            border: 1px solid #ddd;
        }

        .image-container {
            text-align: center;
            padding: 5px;
        }

        .no-image {
            color: #999;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
        }

        .image-error {
            color: #ff0000;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background-color: #fff0f0;
            border: 1px dashed #f00;
        }

        /* Row striping */
        .product-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Print Specific */
        @media print {
            body { 
                font-size: 9px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
            .page-container { 
                max-width: none;
                margin: 0;
                padding: 0;
            }
            
            .product-table {
                page-break-inside: auto;
            }
            
            .product-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .foto-toko {
                max-height: 120px;
            }
        }
        
        .info-period {
            text-align: center;
            margin-bottom: 15px;
            font-style: italic;
            color: #666;
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
                    <div>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></div>
                </div>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title"><?= $title; ?></div>
        
        <!-- Period Info -->
        <div class="info-period">
            Periode: <?= date('d/m/Y', strtotime($from_date)) ?> - <?= date('d/m/Y', strtotime($to_date)) ?>
        </div>

        <!-- Product Table -->
        <div class="product-section">
            <table class="product-table">
                <thead>
                    <tr>
                        <th class="col-username">Username</th>
                        <th class="col-pasar">Nama Pasar</th>
                        <th class="col-toko">Nama Toko</th>
                        <th class="col-foto">Foto Toko</th>
                        <th class="col-foto">Foto Produk</th>
                        <th class="col-foto">Foto Branding</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($get_data)) : ?>
                        <?php foreach ($get_data as $key) : ?>
                            <tr>
                                <td class="text-left"><?= !empty($key['username']) ? $key['username'] : '-' ?></td>
                                <td class="text-left"><?= !empty($key['nama_pasar']) ? $key['nama_pasar'] : '-' ?></td>
                                <td class="text-left"><?= !empty($key['nama_toko']) ? $key['nama_toko'] : '-' ?></td>
                                <td class="text-center">
                                    <?php if (!empty($key['foto_toko'])) : ?>
                                        <div class="image-container">
                                            <img src="<?= $key['foto_toko'] ?>" class="foto-toko" alt="Foto Toko">
                                            <!-- <img src="<?= $key['foto_toko'] ?>" alt="Foto Toko" width="150px" height="200px"> -->
                                        </div>
                                    <?php else : ?>
                                        <div class="image-error">
                                            Gagal memuat gambar<br>
                                            <small><?= !empty($key['foto_url']) ? $key['foto_url'] : 'URL tidak tersedia' ?></small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($key['foto_produk'])) : ?>
                                        <div class="image-container">
                                            <img src="<?= $key['foto_produk'] ?>" class="foto-toko" alt="Foto Toko">
                                            <!-- <img src="<?= $key['foto_produk'] ?>" alt="Foto Toko" width="150px" height="200px"> -->
                                        </div>
                                    <?php else : ?>
                                        <div class="image-error">
                                            Gagal memuat gambar<br>
                                            <small><?= !empty($key['foto_url_produk']) ? $key['foto_url_produk'] : 'URL tidak tersedia' ?></small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($key['foto_branding'])) : ?>
                                        <div class="image-container">
                                            <img src="<?= $key['foto_branding'] ?>" class="foto-toko" alt="Foto Toko">                                            
                                        </div>
                                    <?php else : ?>
                                        <div class="image-error">
                                            Gagal memuat gambar<br>
                                            <small><?= !empty($key['foto_url_branding']) ? $key['foto_url_branding'] : 'URL tidak tersedia' ?></small>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data untuk ditampilkan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>