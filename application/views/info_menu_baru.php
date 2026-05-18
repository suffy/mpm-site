<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MPM | Informasi Personalia</title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        /* Popup Wrapper dengan efek blur yang lebih halus */
        .popup-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 16px;
        }
        
        /* Container dengan desain lebih modern */
        .popup-container {
            background: white;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 720px;
            padding: 40px;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalAppear 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalAppear {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Header dengan desain yang lebih elegan */
        .popup-header {
            margin-bottom: 28px;
            padding-right: 40px;
        }
        
        .popup-header h1 {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .popup-header .subtitle {
            color: #64748b;
            font-size: 15px;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .popup-header .subtitle span {
            width: 6px;
            height: 6px;
            background: #dc2626;
            border-radius: 50%;
            display: inline-block;
        }
        
        /* Content Area dengan spacing yang lebih baik */
        .popup-content {
            margin: 32px 0 36px;
        }
        
        /* Info Card dengan desain lebih menarik */
        .info-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 24px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .user-greeting {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px dashed #e2e8f0;
        }
        
        .user-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
        
        .user-greeting h3 {
            color: #0f172a;
            font-size: 18px;
            font-weight: 600;
        }
        
        .user-greeting p {
            color: #475569;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .info-message {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }
        
        .info-message p {
            color: #334155;
            line-height: 1.7;
            font-size: 15px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .info-icon {
            width: 28px;
            height: 28px;
            background: #fee2e2;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            font-size: 16px;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        /* Image Container dengan desain yang lebih baik */
        .image-container {
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .image-container:hover {
            transform: scale(1.02);
        }
        
        .image-container img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }
        
        .image-caption {
            background: white;
            padding: 12px 16px;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .image-caption i {
            font-style: normal;
            width: 20px;
            height: 20px;
            background: #e2e8f0;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Zoom Modal Styles */
        .zoom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            z-index: 2000;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .zoom-modal.active {
            display: flex;
            opacity: 1;
        }

        .zoom-modal-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .zoom-modal img {
            max-width: 95%;
            max-height: 95vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .zoom-modal.active img {
            transform: scale(1);
        }

        .zoom-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 32px;
            font-weight: 300;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 2001;
        }

        .zoom-close:hover {
            background: #ef4444;
            transform: rotate(90deg) scale(1.1);
            border-color: #ef4444;
        }

        .zoom-caption {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            color: white;
            padding: 12px 24px;
            border-radius: 40px;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .zoom-caption i {
            font-style: normal;
            background: rgba(255, 255, 255, 0.2);
            width: 24px;
            height: 24px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Hint untuk klik gambar */
        .image-hint {
            text-align: center;
            margin-top: 8px;
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .image-hint span {
            background: #e2e8f0;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        /* Actions dengan desain button yang lebih modern */
        .popup-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 36px;
        }
        
        .popup-button {
            width: 100%;
            padding: 16px 28px;
            border-radius: 60px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            letter-spacing: 0.02em;
        }
        
        .primary-button {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.5);
        }
        
        .primary-button:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -8px rgba(37, 99, 235, 0.6);
        }
        
        .secondary-button {
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
        }
        
        .secondary-button:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #1e293b;
            transform: translateY(-2px);
        }
        
        /* Close button yang elegan - sekarang tanpa action */
        .popup-close {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: #64748b;
            font-size: 24px;
            font-weight: 300;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
            text-decoration: none;
            pointer-events: auto;
        }
        
        .popup-close:hover {
            background: #ef4444;
            color: white;
            transform: rotate(90deg) scale(1.1);
            border-color: #ef4444;
            box-shadow: 0 10px 20px -8px #ef4444;
        }
        
        /* Badge untuk informasi tambahan */
        .feature-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f1f5f9;
            border-radius: 60px;
            font-size: 12px;
            font-weight: 500;
            color: #475569;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .feature-badge.highlight {
            background: #dc2626;
            color: white;
        }
        
        /* Scrollbar yang lebih halus */
        .popup-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .popup-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 20px;
            margin: 8px;
        }
        
        .popup-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
            border: 2px solid #f1f5f9;
        }
        
        .popup-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Loading Overlay Styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.active {
            display: flex;
            opacity: 1;
        }

        .loading-content {
            text-align: center;
            color: white;
            animation: fadeInUp 0.5s ease;
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            position: relative;
        }

        .spinner-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top-color: #ffffff;
            border-bottom-color: #ffffff;
            animation: spin 1.5s linear infinite;
        }

        .spinner-ring:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 10px;
            left: 10px;
            border-top-color: #ffd700;
            border-bottom-color: #ffd700;
            animation: spin 2s linear infinite reverse;
        }

        .spinner-ring:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 20px;
            left: 20px;
            border-top-color: #ff6b6b;
            border-bottom-color: #ff6b6b;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .loading-subtext {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 30px;
            font-weight: 400;
        }

        .loading-progress {
            width: 300px;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            margin: 20px auto;
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #ffffff, #ffd700, #ff6b6b);
            border-radius: 30px;
            transition: width 0.3s ease;
            position: relative;
            animation: progressPulse 1.5s ease infinite;
        }

        .loading-percentage {
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
            color: rgba(255, 255, 255, 0.9);
        }

        .loading-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            opacity: 0.6;
            animation: dotPulse 1.5s ease infinite;
        }

        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes progressPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        @keyframes dotPulse {
            0%, 100% { 
                transform: scale(1);
                opacity: 0.6;
            }
            50% { 
                transform: scale(1.5);
                opacity: 1;
            }
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .popup-container {
                padding: 28px 20px;
            }
            
            .popup-header h1 {
                font-size: 24px;
            }
            
            .popup-close {
                top: 20px;
                right: 20px;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .zoom-modal-content {
                padding: 20px;
            }

            .zoom-close {
                top: 10px;
                right: 10px;
                width: 40px;
                height: 40px;
                font-size: 28px;
            }

            .zoom-caption {
                bottom: 20px;
                padding: 8px 16px;
                font-size: 12px;
            }

            .loading-text {
                font-size: 24px;
            }

            .loading-progress {
                width: 250px;
            }
        }

        /* Animasi untuk icon dan elemen */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .info-icon {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <div class="popup-wrapper" id="popup">
        <div class="popup-container">
            <!-- Header dengan desain lebih informatif -->
            <div class="popup-header">
                <h1>Informasi Perilisan Menu Baru</h1>
            </div>
            
            <!-- Content dengan layout yang lebih terstruktur -->
            <div class="popup-content">
                <div class="info-card">
                    <!-- User Greeting dengan avatar -->
                    <div class="user-greeting">
                        <div class="user-avatar">
                            <?= substr($this->session->userdata('username'), 0, 1) ?>
                        </div>
                        <div>
                            <h3>Halo, <?= $this->session->userdata('username') ?>! 👋</h3>
                            <p>Ada pembaruan penting untuk Anda</p>
                        </div>
                    </div>
                    
                    <!-- Informasi dengan icon yang menarik -->
                    <div class="info-message">
                        <p>
                            <span class="info-icon">📢</span>
                            <span>Kami merilis menu baru <strong>"Pareto Management Account"</strong>. Saat ini hanya bisa diakses oleh Deltomed dan MPM.</span>
                        </p>
                    </div>
                    
                    <!-- Feature badges untuk menunjukkan akses -->
                    <div style="margin-bottom: 20px;">
                        <span class="feature-badge highlight">✨ Fitur Baru</span>
                        <span class="feature-badge">🔒 Deltomed</span>
                        <span class="feature-badge">🔒 MPM</span>
                        <span class="feature-badge">📊 Pareto Analysis</span>
                    </div>
                    
                    <!-- Image dengan caption yang informatif dan bisa diklik -->
                    <div class="image-container" onclick="openZoomModal()">
                        <img src="<?= base_url().'assets/img/pareto.jpg' ?>" alt="Pareto Management Account Preview" id="previewImage">
                        <div class="image-caption">
                            <i>📸</i>
                            Preview Menu: Sales → Pareto Management Account
                        </div>
                    </div>
                    
                    <!-- Hint untuk klik gambar -->
                    <div class="image-hint">
                        <span>🖱️ Klik gambar untuk tampilan full screen</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions dengan button yang lebih jelas hierarkinya -->
            <div class="popup-actions">
                <a href="javascript:void(0);" onclick="showLoading()" class="popup-button primary-button">
                    ✓ Saya sudah mengerti, jangan tampilkan kembali
                </a>
                <a href="<?= base_url() ?>management_office/dashboard_new" class="popup-button secondary-button">
                    ⏰ Ingatkan saya nanti
                </a>
            </div>
            
            <!-- Close button yang elegan - tanpa action -->
            <a class="popup-close" href="javascript:void(0);" onclick="return false;" title="Tutup">×</a>
        </div>
    </div>

    <!-- Zoom Modal untuk full page image -->
    <div class="zoom-modal" id="zoomModal" onclick="closeZoomModal(event)">
        <div class="zoom-modal-content">
            <img src="<?= base_url().'assets/img/pareto.jpg' ?>" alt="Pareto Management Account Full View" id="zoomedImage">
            <div class="zoom-close" onclick="closeZoomModal(event)">×</div>
            <div class="zoom-caption">
                <i>📸</i>
                Preview Menu: Sales → Pareto Management Account
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
            <h2 class="loading-text">Memproses</h2>
            <p class="loading-subtext">Menyimpan preferensi Anda...</p>
            <div class="loading-progress">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <div class="loading-percentage" id="loadingPercentage">0%</div>
            <div class="loading-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka zoom modal
        function openZoomModal() {
            const modal = document.getElementById('zoomModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Mencegah scroll pada background
        }

        // Fungsi untuk menutup zoom modal
        function closeZoomModal(event) {
            // Mencegah penutupan jika klik pada gambar
            if (event.target.closest('img') && !event.target.classList.contains('zoom-close')) {
                return;
            }
            
            const modal = document.getElementById('zoomModal');
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Mengembalikan scroll
        }

        // Menutup modal dengan tombol ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('zoomModal');
                if (modal.classList.contains('active')) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Juga tutup loading overlay jika ESC ditekan
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay.classList.contains('active')) {
                    loadingOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });

        // Mencegah event bubbling saat klik pada gambar di modal
        document.getElementById('zoomedImage').addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Fungsi untuk menampilkan loading
        function showLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            const progressBar = document.getElementById('progressBar');
            const percentageText = document.getElementById('loadingPercentage');
            
            // Tampilkan loading overlay
            loadingOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Mencegah scroll
            
            // Simulasi progress loading
            let progress = 0;
            const interval = setInterval(function() {
                progress += Math.random() * 15;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    
                    // Redirect setelah loading selesai
                    setTimeout(function() {
                        window.location.href = '<?= base_url() ?>management_office/insert_konfirmasi_informasi';
                    }, 500);
                }
                
                // Update progress bar
                progressBar.style.width = progress + '%';
                percentageText.textContent = Math.round(progress) + '%';
                
                // Update text berdasarkan progress
                const loadingText = document.querySelector('.loading-subtext');
                if (progress < 30) {
                    loadingText.textContent = 'Menyimpan preferensi Anda...';
                } else if (progress < 60) {
                    loadingText.textContent = 'Hampir selesai...';
                } else if (progress < 90) {
                    loadingText.textContent = 'Finalisasi...';
                } else {
                    loadingText.textContent = 'Mengalihkan...';
                }
            }, 200);
        }
    </script>
</body>
</html>