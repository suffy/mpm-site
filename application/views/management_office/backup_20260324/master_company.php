<style>
    :root {
        --primary: #A8E6CF;
        --secondary: #B8E6B8;
        --text: #2C3E50;
    }

    /* body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', sans-serif;
        padding: 20px;
    } */

    .export-section {
        /* background: linear-gradient(145deg, #ffffff 0%, #f0f8ff 100%); */
        border-radius: 20px;
        /* padding: 2rem; */
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid #e0e0e0;
        /* border: 1px solid #B8E6B8; */
        padding : 2rem;
        margin: 10px;
    }

    .export-title {
        /* color: var(--text); */
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .export-btn {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text);
        border: none;
        padding: 15px 25px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        cursor: pointer;
        transition: all 0.4s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(168, 230, 207, 0.4);
    }

    .export-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(168, 230, 207, 0.6);
    }

    .export-btn i {
        transition: transform 0.3s ease;
    }

    .export-btn:hover i {
        transform: rotate(360deg);
    }

    .export-btn.success {
        background: linear-gradient(135deg, #90EE90 0%, #98FB98 100%);
        animation: pulse 0.6s ease-in-out;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) translateY(-3px); }
        50% { transform: scale(1.05) translateY(-3px); }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .loading i {
        animation: spin 1s linear infinite;
    }

    a:hover{
    color: black;
    }

    /* Dark mode khusus tombol export-btn*/
    [data-bs-theme="dark"] .export-btn {
        background: linear-gradient(135deg, #444 0%, #666 100%);
        color: #fff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
    }

</style>
</head>
<body>
    <div class="export-section">
        <h3 class="export-title">
            <i class="fas fa-database"></i> Export Data Master
        </h3>
        <a href="<?php echo base_url() . "management_office/export_master_company"; ?>" class="export-btn" onclick="handleExport(this)">
            <i class="fas fa-download"></i>
            Export Data Master Company
        </a>
    </div>

    <script>
        function handleExport(button) {
            const originalHTML = button.innerHTML;
            const originalClass = button.className;
            
            // Show loading
            button.classList.add('loading');
            button.innerHTML = '<i class="fas fa-spinner"></i> Mengexport...';
            
            setTimeout(() => {
                // Show success
                button.classList.remove('loading');
                button.classList.add('success');
                button.innerHTML = '<i class="fas fa-check"></i> Export Berhasil!';
                
                // Reset
                setTimeout(() => {
                    button.className = originalClass;
                    button.innerHTML = originalHTML;
                }, 2000);
            }, 1500);
        }
    </script>
</body>
</html>