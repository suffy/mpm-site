<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pengajuan Claim</title>
    <style type="text/css">
        /* Reset dan base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            margin-bottom: 25px;
            font-size: 16px;
            color: #2c3e50;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
        }
        
        .info-box p {
            margin-bottom: 10px;
            font-size: 15px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .data-table th {
            background-color: #2c3e50;
            color: white;
            padding: 14px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        
        .data-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #eaeaea;
            font-size: 14px;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .label {
            color: #555;
            font-weight: 500;
            min-width: 200px;
        }
        
        .value {
            color: #222;
            font-weight: 400;
        }
        
        .amount-highlight {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 12px 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
            font-size: 16px;
            font-weight: 600;
            color: #1b5e20;
        }
        
        .amount-highlight span {
            font-size: 18px;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 35px 0 20px;
        }
        
        .button {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            transition: all 0.3s ease;
            min-width: 160px;
        }
        
        .button-accept {
            background-color: #27ae60;
            color: white;
            border: 2px solid #27ae60;
        }
        
        .button-accept:hover {
            background-color: #219653;
            border-color: #219653;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }
        
        .button-reject {
            background-color: #F7E396;
            color: #F7E396;
            border: 2px solid #F7E396;
        }
        
        .button-reject:hover {
            background-color: #FACE68;
            color: white;
            border-color: #FACE68;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(247, 230, 150, 0.3);
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #eaeaea;
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
        }
        
        .urgency-badge {
            display: inline-block;
            background-color: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .note {
            font-size: 13px;
            color: #666;
            font-style: italic;
            margin-top: 25px;
            text-align: center;
        }
        
        /* Responsive design */
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }
            
            .button {
                width: 100%;
                max-width: 280px;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Verifikasi Pengajuan Claim</h1>
            <p>Sistem Management Klaim - MPM</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Dear Bapak/Ibu <strong><?= $username_to; ?></strong>,</p>
                <p>Berikut adalah Pengajuan Claim yang membutuhkan respon anda segera :</p>
            </div>
            
            <div class="urgency-badge">
                ⏰ Membutuhkan Tindakan Segera
            </div>
            
            <div class="info-box">
                <p>Mohon untuk meninjau detail pengajuan di bawah ini dan mengambil tindakan yang diperlukan.</p>
            </div>
            
            <!-- Data Table -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th colspan="2">Detail Pengajuan Claim</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">Status Klaim</td>
                        <td class="value"><strong><?= $nama_status; ?></strong></td>
                    </tr>
                    <tr>
                        <td class="label">No. Ajuan MPM</td>
                        <td class="value"><strong><?= $nomor_ajuan; ?></strong></td>
                    </tr>
                    <tr>
                        <td class="label">No. Klaim</td>
                        <td class="value"><?= $nomor_klaim; ?></td>
                    </tr>
                    <tr>
                        <td class="label">No. Invoice/SKP/Trading Term</td>
                        <td class="value"><?= $nomor_invoice; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Channel</td>
                        <td class="value" style="text-transform: uppercase;"><?= $channel; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kategori</td>
                        <td class="value"><?= $kategori; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Key Account</td>
                        <td class="value"><?= $key_account; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Subbranch</td>
                        <td class="value"><?= $nama_comp . ' (' . $site_code . ')'; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Periode</td>
                        <td class="value"><?= $periode_start . ' - ' . $periode_end; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Keterangan</td>
                        <td class="value"><?= $keterangan; ?></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Amount Highlight -->
            <div class="amount-highlight">
                Nominal DPP: <span>Rp. <?= number_format($nominal_dpp, 0, ',', '.'); ?></span>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="<?= $link_web_mpm; ?>" class="button button-reject">Menuju Web MPM</a>
            </div>
            
            <div class="note">
                <p>Silakan klik tombol di atas untuk mengambil tindakan. Tindakan Anda akan dicatat dalam sistem.</p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>Email ini dikirim secara otomatis dari Sistem Management Klaim MPM.</p>
                <p>Mohon tidak membalas email ini. Untuk bantuan, hubungi tim IT MPM.</p>
                <p>&copy; <?= date('Y'); ?> MPM System. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>