<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Pop Up</title>
    <style>
        /* Reset dan styling dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        /* Styling popup */
        .popup-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        }
        
        .popup-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            padding: 30px;
            position: relative;
            animation: slideUp 0.4s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Header popup */
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .popup-header h1 {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
        }
        
        /* Tombol close */
        .popup-close {
            position: absolute;
            top: 15px;
            right: 20px;
            width: 32px;
            height: 32px;
            background-color: #f1f3f6;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: #7f8c8d;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .popup-close:hover {
            background-color: #e74c3c;
            color: white;
        }
        
        /* Konten popup */
        .popup-content {
            margin-bottom: 25px;
        }
        
        .info-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-title {
            font-weight: 600;
            color: #3498db;
            margin-bottom: 8px;
            font-size: 18px;
        }
        
        .info-desc {
            color: #34495e;
            line-height: 1.6;
            font-size: 16px;
        }
        
        /* Tombol aksi */
        .popup-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px;
        }
        
        .popup-button {
            flex: 1;
            min-width: 200px;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        
        .primary-button {
            background-color: #3498db;
            color: white;
        }
        
        .primary-button:hover {
            background-color: #2980b9;
        }
        
        .secondary-button {
            background-color: #f1f3f6;
            color: #34495e;
        }
        
        .secondary-button:hover {
            background-color: #e4e7ec;
        }
        
        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsif */
        @media (max-width: 600px) {
            .popup-container {
                padding: 20px;
            }
            
            .popup-header h1 {
                font-size: 20px;
            }
            
            .popup-actions {
                flex-direction: column;
            }
            
            .popup-button {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="popup-wrapper" id="popup">
        <div class="popup-container">
            <!-- Header Popup -->
            <div class="popup-header">
                <h1>Informasi Closing Web November 2025 !</h1>
            </div>
            
            <!-- Isi Popup -->
            <div class="popup-content">
                <div class="info-item">
                    <div class="info-title">Dear All DP, Hoo, Rom, Am</div>
                    <div class="info-desc">
                        Batas akhir upload / pengiriman data (sales november + stock november) adalah di 4 desember 2025 pukul 17.00 wib.   
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-desc">
                        Untuk pengiriman data di luar batas waktu tersebut, maka silahkan kirimkan berita acara kepada kami dan ditandatangai oleh HOO masing-masing. Lengkap dengan alasan keterlambatan + total sales november nya.   
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-desc">
                        Sehingga, harapannya raw data closing november bisa mulai tersedia di Jumat, 5 desember 2025. 
                    </div>
                </div>
                
                <!-- <div class="info-item">
                    <div class="info-title">2. Report Pengajuan Retur</div>
                    <div class="info-desc">
                        Kini memberikan informasi qty tolakan (khusus principal Deltomed).
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-title">3. Claim</div>
                    <div class="info-desc">
                        Kami merilis menu baru claim, kami beri nama "Report Claim Availability". Berfungsi mengetahui DP mana saja yang belum dan sudah mengajukan claim. Cocok bagi AM, ROM, HOO untuk memonitor claim DP masing-masing. Klik menu claim -> Report Claim Availability.
                    </div>
                </div> -->
            </div>
            
            <!-- Tombol Aksi -->
            <div class="popup-actions">
                <a href="<?php echo base_url() ?>management_office/insert_konfirmasi_informasi" class="popup-button primary-button">
                    Terimakasih, saya sudah paham informasi diatas
                </a>
                <a href="<?php echo base_url() ?>management_office/dashboard" class="popup-button secondary-button">
                    Saya akan baca nanti
                </a>
            </div>
            
            <!-- Tombol Close Popup -->
            <a class="popup-close" href="<?php echo base_url() ?>management_office/dashboard">×</a>
        </div>
    </div>

    <script>
        // Menutup popup ketika tombol close diklik
        document.querySelector('.popup-close').addEventListener('click', function(e) {
            e.preventDefault();
            // document.getElementById('popup').style.display = 'none';
        });
        
        // Menutup popup ketika area di luar popup diklik
        document.getElementById('popup').addEventListener('click', function(e) {
            if (e.target === this) {
                // this.style.display = 'none';
            }
        });
        
        // Menutup popup dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // document.getElementById('popup').style.display = 'none';
            }
        });
    </script>
</body>
</html>