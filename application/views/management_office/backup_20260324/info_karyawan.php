<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Pending Biop</title>
    <style>
        /* Style dasar dipertahankan dengan sentuhan minimalis */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .popup-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .popup-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), 0 8px 24px rgba(0, 0, 0, 0.06);
            width: 95%;
            max-width: 800px;
            padding: 32px;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .popup-header h1 {
            color: #dc2626;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }
        
        /* Minimalis: Tabel lebih bersih */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        table th {
            padding: 16px;
            text-align: left;
            background-color: #fafbfc;
            color: #1e293b;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 1px solid #e2e8f0;
        }
        
        table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        
        table tr:last-child td {
            border-bottom: none;
        }
        
        .row-highlight {
            background-color: #fef9e7;
        }
        .row-highlight td {
            font-weight: 600;
            color: #854d0e;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background-color: #fee2e2;
            color: #dc2626;
            display: inline-block;
            line-height: 1;
        }

        .popup-close {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 32px;
            height: 32px;
            background-color: #f1f5f9;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: #64748b;
            font-size: 20px;
            transition: all 0.2s;
        }
        
        .popup-close:hover {
            background-color: #e2e8f0;
            color: #334155;
            transform: rotate(90deg);
        }
        
        .popup-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }
        
        .popup-button {
            flex: 1;
            min-width: 200px;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            border: none;
        }
        
        .primary-button {
            background-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 6px -2px rgba(59, 130, 246, 0.3);
        }
        
        .primary-button:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 8px 12px -4px rgba(59, 130, 246, 0.4);
        }
        
        .secondary-button {
            background-color: white;
            color: #334155;
            border: 1.5px solid #e2e8f0;
        }
        
        .secondary-button:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }

        /* Info item minimalis */
        .info-item {
            margin: 24px 0;
        }
        
        .info-title {
            font-weight: 600;
            color: #0f172a;
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .info-desc {
            color: #475569;
            line-height: 1.6;
            font-size: 15px;
            padding: 12px 16px;
            background-color: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #dc2626;
        }

        /* Scrollbar minimalis */
        .popup-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .popup-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }
        
        .popup-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        
        .popup-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="popup-wrapper" id="popup">
        <div class="popup-container">
            <div class="popup-header">
                <h1>Perhatian: Data Personalia Anda Tidak Ditemukan</h1>
            </div>
            
            <div class="popup-content">
                <div class="info-item">
                    <div class="info-title">Halo, <?php echo $this->session->userdata('username'); ?></div>
                    <div class="info-desc">
                        Mohon lengkapi data personalia Anda terlebih dahulu sebelum mengakses fitur lainnya. Terima kasih.
                    </div>
                </div>

            </div>
            
            <div class="popup-actions">
                <a href="<?php echo base_url() ?>management_karyawan" class="popup-button primary-button">
                    Lengkapi data sekarang
                </a>
                <!-- <a href="<?php echo base_url() ?>management_office/dashboard" class="popup-button secondary-button">
                    Lengkapi data nanti
                </a> -->
            </div>
            
            <!-- <a class="popup-close" href="<?php echo base_url() ?>management_office/dashboard">×</a> -->
            <a class="popup-close" href="<?php echo base_url() ?>management_karyawan">×</a>
        </div>
    </div>
</body>
</html>