
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        #form_spk {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 1rem;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 1rem;
        }

        .image-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .document-image {
            width: 100%;
            max-width: 600px;
            /* height: 400px; */
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .document-image:hover {
            transform: scale(1.02);
        }

        .no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .document-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .info-header {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-header::before {
            content: "ℹ️";
            font-size: 1.2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }

        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            color: #212529;
            font-weight: 500;
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            #form_spk {
                font-size: 1.5rem;
            }

            .content-card {
                padding: 1.5rem;
            }

            .document-image {
                height: 300px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .container-fluid {
                padding: 10px;
            }

            #form_spk {
                font-size: 1.3rem;
            }

            .content-card {
                padding: 1rem;
            }

            .document-image {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluida">
        <h2 id="form_spk"><?= $title; ?></h2>

        <div class="content-card">
            <div class="image-container">
                <!-- Simulasi kondisi jika ada gambar -->
                <img src="<?= $image ?>" 
                     alt="Document Image" 
                     class="document-image">
            </div>

            <div class="document-info">
                <div class="info-header">
                    Document Information
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">No DO</div>
                        <div class="info-value"><?= $nodo ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tgl DO</div>
                        <div class="info-value"><?= $tgldo ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No PO</div>
                        <div class="info-value"><?= $nopo ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tgl PO</div>
                        <div class="info-value"><?= $tglpo ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>