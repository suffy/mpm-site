<!-- application/views/layouts/main.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $page_title; ?></title>
  
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Responsive Bootstrap 4 Dashboard Template">
  <meta name="author" content="BootstrapDash">

  <!-- PRELOAD THEME SCRIPT - Ditempatkan paling atas untuk mencegah FOUC/Flash -->
  <script>
    (function() {
      // Fungsi untuk mendapatkan tema yang disimpan atau preferensi sistem
      const getTheme = () => {
        try {
          const storedTheme = localStorage.getItem('theme');
          if (storedTheme === 'dark' || storedTheme === 'light') {
            return storedTheme;
          }
          // Cek preferensi sistem
          return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        } catch (e) {
          return 'light';
        }
      };
      
      // Terapkan tema sebelum browser merender halaman
      const theme = getTheme();
      document.documentElement.setAttribute('data-bs-theme', theme);
      
      // Sembunyikan konten sampai theme diterapkan (opsional, untuk mencegah flash)
      document.documentElement.style.visibility = 'hidden';
      document.documentElement.style.opacity = '0';
      
      // Tampilkan kembali setelah theme diterapkan
      window.addEventListener('load', function() {
        document.documentElement.style.visibility = '';
        document.documentElement.style.opacity = '';
      });
    })();
  </script>

  <!-- Critical CSS inline untuk mencegah flash -->
  <style>
    /* Sembunyikan konten sampai theme siap */
    html { 
      visibility: visible; 
      opacity: 1; 
      transition: opacity 0.1s ease;
    }
    
    /* Pastikan background sesuai theme saat load */
    [data-bs-theme="dark"] body {
      /* background-color: #1a1d21 !important; */
      background-color: #000 !important;
    }
    
    [data-bs-theme="light"] body {
      background-color: #faf9f8 !important;
    }

    /* Hilangkan padding default pada container */
    .no-padding-container {
      padding-left: 0 !important;
      padding-right: 0 !important;
      margin-left: 0 !important;
      margin-right: 0 !important;
      width: 100%;
    }
  </style>

  <!-- Vendor CSS -->
  <link href="<?= base_url() ?>assets/css/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/lib/typicons.font/typicons.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
  
  <!-- Azia CSS -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/azia.css">
  
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css" />
  
  <!-- Font Awesome -->
  <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/fontawesome.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/brands.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/solid.css" rel="stylesheet">
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

  <!-- select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    * {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      text-decoration: none;
      list-style: none;
      scroll-behavior: smooth;
    }

    /* Hilangkan padding default pada body dan container */
    body {
      margin: 0;
      padding: 0;
    }

    /* Container untuk konten tanpa padding */
    .content-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 25px 0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      font-size: 0.9em;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
      overflow: hidden;
    }

    table thead tr {
      background-color: var(--bs-dark-border-subtle);
      color: var(--bs-dark-text-emphasis);
      text-align: left;
      font-weight: 600;
      border-bottom: 2px solid #FBF6E9;
    }

    table th,
    table td {
      padding: 12px 15px;
      text-align: left;
      border: 0.7px solid var(--bs-dark-border-subtle);
      border-radius: 1px;
      opacity: 0.8;
    }

    td:hover {
      transform: scale(1.01);
      background-color: var(--bs-dark-border-subtle);
    }

    th:hover {
      transform: scale(1.01);
      background-color: var(--bs-dark-bg-subtle);
    }

    .btn-submit {
      color: #f0f0f0;
      background-color: #007F73;
      border-radius: 10px;
      border: 1px solid #222831;
    }

    .btn-submit:hover {
      color: #f0f0f0;
      background-color: #647D87;
    }

    .btn-dark {
      color: #f0f0f0;
      background-color: #31363F;
      border-radius: 10px;
      border: 1px solid #222831;
    }

    .btn-dark:hover {
      color: #f0f0f0;
      background-color: #31363F;
    }

    a:link, a:visited, a:hover, a:active {
      text-decoration: none;
    }
    
    .btn-custom {
      background-color: white;
      color: black;
      border-radius: 5px;
      border: 2px solid red;
      padding: 2px;
      width: 10px;
      height: 10px;
    }

    .btn-ok {
      background-color: #525CEB;
      color: #f0f0f0;
      padding: 5px;
      border-radius: 5px;
    }

    .btn-no {
      background-color: #647D87;
      color: #f0f0f0;
      padding: 5px;
      border-radius: 5px;
    }

    .btn-ok:hover {
      cursor: pointer;
    }

    .btn-export {
      color: #f0f0f0;
      background-color: #D04848;
      border-radius: 10px;
    }

    .btn-export:hover {
      color: #f0f0f0;
      background-color: #7077A1;
    }

    .btn-all {
      color: #f0f0f0;
      background-color: #6895D2;
      border-radius: 10px;
    }

    .btn-all:hover {
      color: #f0f0f0;
      background-color: #7077A1;
    }

    .btn-null {
      color: black;
      background-color: #F9EFDB;
      border-radius: 10px;
      border: 2px solid black;
    }

    .btn-pendingmpm {
      color: #f0f0f0;
      background-color: #FE7A36;
      border-radius: 10px;
      border: 2px solid black;
    }

    .btn-pendingprincipal {
      color: #f0f0f0;
      background-color: #D04848;
      border-radius: 10px;
      border: 2px solid black;
    }

    .btn-back {
      color: #f0f0f0;
      background-color: #9BB0C1;
      border-radius: 10px;
      border: 1px solid black;
    }

    .btn-ikut {
      color: #000;
      background-color: #BED1CF;
      border-radius: 10px;
      border: 2px solid black;
    }

    .btn-tidakikut {
      color: #fff;
      background-color: #3C3633;
      border-radius: 10px;
      border: 2px solid black;
    }

    .btn-tidakikut:hover {
      color: black;
      background-color: #747264;
    }

    .btn-loading {
      color: #f0f0f0;
      background-color: #B4B4B8;
      border-radius: 10px;
    }

    .btn-pendingdp {
      color: #f0f0f0;
      background-color: #7077A1;
      border-radius: 10px;
      border: 2px solid black;
    }

    input[type=button] {
      font-weight: bold;
      color: white;
      background-color: transparent;
      text-align: center;
      border: none;
    }

    .accordion {
      cursor: pointer;
      padding: 1px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.2s;
      border: 3px solid darkslategray;
      border-radius: 14px;
      margin-top: 1rem;
    }
      
    .btn-submit-black {
      font-size: 16px;
      font-weight: bold;
      border-radius: 6px;
      border: 0.5px solid var(--bs-dark-bg-subtle);
      border-color: var(--bs-dark-text-emphasis);
      cursor: pointer;
      transition: .5s ease;
      padding: 10px 8px;
    }

    .btn-submit-black:hover {
      color: var(--bs-dark-text-emphasis);
      border-color: var(--bs-dark-text-emphasis);
      background-color: var(--bs-dark-border-subtle);
      border: 0.5px solid var(--bs-dark);
    }

    .btn-submit-orange {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      background-color: #E8751A;
      color: #fff;
    }

    .btn-submit-orange:hover {
      color: #fff;
      background: #1d1d1d;
    }
      
    .btn-submit-red {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      background-color: #D20062;
      color: #fff;
    }

    .btn-submit-red:hover {
      color: #fff;
      background: #1d1d1d;
    }

    .btn-submit-blue {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      background-color: #6AD4DD;
      color: #fff;
    }

    .btn-submit-blue:hover {
      color: #fff;
      background: #1d1d1d;
    }

    .btn-submit-approve {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      cursor: pointer;
      transition: .5s ease;
      background-color: #48B3AF;
      color: #fff;
    }

    .btn-submit-approve:hover {
      color: #fff;
      background: #48B3AF;
      transform: scale(1.1);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .btn-submit-revisi {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      cursor: pointer;
      transition: .5s ease;
      background-color: #FF3F7F;
      color: #fff;
    }

    .btn-submit-revisi:hover {
      color: #fff;
      background: #FF3F7F;
      transform: scale(1.1);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-submit-back {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      cursor: pointer;
      transition: .5s ease;
      background-color: #44444E;
      color: #fff;
    }

    .btn-submit-back:hover {
      color: #fff;
      background: #44444E;
      transform: scale(1.1);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-submit-grey {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      background-color: #F0EBE3;
      color: #1d1d1d;
    }

    .btn-submit-grey:hover {
      color: #fff;
      background: #1d1d1d;
    }

    .btn-submit-cream {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      background-color: #FFF5E0;
      color: #1d1d1d;
    }

    .btn-submit-cream:hover {
      color: #fff;
      background: #B99470;
    }

    .btn-submit-green {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #016B61;
      cursor: pointer;
      transition: .5s ease;
      background-color: #70B2B2;
      color: #fff;
    }

    .btn-submit-green:hover {
      color: #016B61;
      background: #70B2B2;
    }
    
    .nav-link-new {
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: .5s ease;
      text-decoration: none;
      color: var(--bs-dark-text-emphasis);
      padding: 5px 0;
    }

    .nav-link-new:hover {
      color: var(--bs-dark-text-emphasis);
    }

    .btn-delete {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      color: #f0f0f0;
      background-color: brown;
      padding: 5px 10px;
    }

    .btn-delete:hover {
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      border: 1px solid #1d1d1d;
      cursor: pointer;
      transition: .5s ease;
      color: #f0f0f0;
      background-color: #1d1d1d;
      padding: 5px 10px;
    }

    .title-square {
      color: #1d1d1d;
      padding: 10px;
      border-radius: 5px;
      font-weight: 500;
      font-size: 20px;
      box-shadow: 1px 1px 3px rgba(0,0,0,0.12), 1px 1px 2px rgba(0,0,0,0.24);
      border-width: 1px;
      border-style: solid;
      border-color: '#1d1d1d';
      border-radius: 5px;
    }

    .pending-scm {
      background-color: #e6f7ed;
      color: #1f9254;
    }
    
    .pending-finance {
      background-color: #fbe7e8;
      color: #d11a2a;
    }
    
    .pending-rilis-po {
      background-color: #e3f1fc;
      color: #2b7cc0;
    }
    
    .finish {
      background-color: #698474;
      color: #fff;
    }
    
    .status {
      padding: 8px 10px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: bold;
      border: none;
    }

    .delete-button {
      background-color: #ff4d4d;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 11px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .delete-button:hover {
      background-color: #ff3333;
      box-shadow: 0 4px 8px rgba(255, 77, 77, 0.5);
      transform: translateY(-2px);
    }

    .delete-button:active {
      transform: translateY(0);
      box-shadow: 0 1px 3px rgba(255, 77, 77, 0.4);
    }

    .delete-button::before {
      content: "\2716";
      margin-right: 8px;
      font-size: 13px;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .delete-button:hover {
      animation: pulse 0.8s infinite;
    }

    .send-email-button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(76, 175, 80, 0.3);
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .send-email-button:hover {
      background-color: #45a049;
      box-shadow: 0 4px 8px rgba(76, 175, 80, 0.5);
      transform: translateY(-2px);
    }

    .send-email-button:active {
      transform: translateY(0);
      box-shadow: 0 1px 3px rgba(76, 175, 80, 0.4);
    }

    .send-email-button::before {
      content: "\2709";
      margin-right: 8px;
      font-size: 14px;
    }

    .send-email-button:hover {
      animation: pulse 0.8s infinite;
    }

    .main {
      flex: 1;
      padding: 1px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .widget {
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 15px;
      margin-bottom: 15px;
      width: calc(33% - 20px);
      border-radius: 10px;
      transition: transform 0.3s ease;
    }

    .widget h3 {
      margin-bottom: 10px;
    }

    .metric {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }

    .metric .value {
      font-size: 14px;
      font-weight: bold;
      transition: transform 0.3s ease;
    }

    .metric .value:hover,
    .metric .label:hover {
      transform: scale(1.5);
    }

    .widget:nth-child(1),
    .widget:nth-child(3) {
      background-color: var(--bs-dark-border-subtle);
    }

    .widget:nth-child(2) {
      background-color: var(--bs-dark-bg-subtle);
    }

    .widget:nth-child(1):hover,
    .widget:nth-child(2):hover,
    .widget:nth-child(3):hover {
      transform: scale(1.05);
    }

    .product-list {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .product-list li {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
    }
  
    .export-excel-btn {
      display: inline-flex;
      align-items: center;
      padding: 10px 15px;
      background-color: #a8d5ba;
      color: #2c3e50;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .export-excel-btn:hover {
      background-color: #8fc1a9;
      color: #34495e;
      transform: scale(1.2);
    }

    .export-excel-btn:before {
      content: "\f1c3";
      font-family: "Font Awesome 5 Free";
      font-weight: 900;
      margin-right: 8px;
    }

    .pastel-orange-btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #FFB347;
      color: #5A4E4E;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pastel-orange-btn:hover {
      background-color: #FFA726;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      transform: scale(1.2);
    }

    .pastel-orange-btn:active {
      background-color: #FF9800;
      transform: translateY(1px);
    }

    .pastel-btn {
      display: inline-block;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pastel-btn:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .pastel-btn:active {
      transform: translateY(1px);
    }

    .pastel-mint {
      background-color: #98D8C8;
      color: #2C3E50;
    }

    .pastel-mint:hover {
      background-color: #7BCBAC;
      transform: scale(1.2);
    }

    .pastel-mint:active {
      background-color: #5EBE90;
    }

    .pastel-lavender {
      background-color: #D4BFFF;
      color: #4A4A4A;
    }

    .pastel-lavender:hover {
      background-color: #C1A3FF;
    }

    .pastel-lavender:active {
      background-color: #AE87FF;
    }

    .pastel-pink {
      background-color: #FFD1DC;
      color: #5A4E4E;
    }

    .pastel-pink:hover {
      background-color: #FFB6C1;
    }

    .pastel-pink:active {
      background-color: #FF9AA2;
    }

    .pastel-yellow {
      background-color: #FDFD96;
      color: #5A4E4E;
    }

    .pastel-yellow:hover {
      background-color: #FCFC83;
    }

    .pastel-yellow:active {
      background-color: #FAFA70;
    }

    .pastel-blue {
      background-color: #AEC6CF;
      color: #2C3E50;
    }

    .pastel-blue:hover {
      background-color: #96B4C2;
    }

    .pastel-blue:active {
      background-color: #7EA2B4;
    }

    .code-block {
      background-color: var(--bs-dark-bg-subtle);
      padding: 20px;
      border-radius: 10px;
      overflow-x: auto;
      margin-bottom: 20px;
      font-family: monospace;
      line-height: 1.6;
    }

    pre {
      margin: 0;
      color: var(--bs-dark-text-emphasis);
      font-size: 17px;
      font-family: Poppins, sans-serif;
    }

    .nav-link-new {
      font-size: 14px;
    }

    .nav-link-new:hover {
      transform: scale(1.1);
    }

    label {
      font-weight: bold;
      font-size: 16px;
    }

    .dashboard {
      display: flex;
      gap: 20px;
      font-family: Arial, sans-serif;
    }

    .card {
      background-color: var(--bs-body-bg);
      border-radius: 20px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      flex: 1;
      border: 2px solid var(--bs-light-bg-subtle);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .title {
      font-size: 14px;
      color: var(--bs-body-color);
    }

    .icon {
      font-size: 18px;
    }

    .main-value {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .sub-value {
      font-size: 13px;
      color: var(--bs-body-color);
    }

    .strike {
      text-decoration: line-through;
    }

    .btn-status {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      transition: 0.3s ease;
      user-select: none;
    }

    .status-closing {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-false {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .status:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="light"] body {
      background-color: #faf9f8 !important;
    }

    [data-bs-theme="light"] .navbar {
      background-color: #faf9f8 !important;
      border-bottom: 0.5px solid #f0f0f0;
      box-shadow: 0 4px 12px -6px rgba(0, 0, 0, 0.2);
    }

    [data-bs-theme="light"] .navbar .nav-link:hover {
      background-color: #f8f2e7;
      border-radius: 6px;
    }

    [data-bs-theme="light"] .navbar .nav-link.active {
      background-color: #f0e9e0;
      border-radius: 6px;
    }

    [data-bs-theme="light"] .navbar .dropdown-menu {
      border: 1px solid #ede5d9;
      box-shadow: 0 8px 16px rgba(0,0,0,0.03);
    }

    [data-bs-theme="light"] .navbar .dropdown-item:hover {
      background-color: #f8f2e7;
    }

    [data-bs-theme="light"] .navbar .btn {
      background-color: #f8f2e7;
      border: none;
    }

    [data-bs-theme="light"] .navbar .btn:hover {
      background-color: #f0e9e0;
    }

    [data-bs-theme="light"] .navbar-brand {
      filter: drop-shadow(0 2px 2px rgba(0,0,0,0.03));
    }

    [data-bs-theme="light"] .navbar-toggler {
      border-color: #e0d5c8;
    }

    [data-bs-theme="light"] .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.5%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar-nav {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      width: 100%;
    }

    @media (min-width: 992px) {
      .navbar-nav {
        flex-direction: row;
        width: auto;
      }
    }

    /* Samakan tinggi Select2 dengan input Bootstrap */
    .select2-container .select2-selection--single {
        height: 38px !important;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }
  </style>
</head>

<body>
  <?php echo $navbar; ?>
  
  <!-- Hapus class container-fluid dan ganti dengan div biasa tanpa padding -->
  <div class="content-wrapper">
    <?php echo $content; ?>
  </div>
  
  <!-- Scripts -->
  <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="<?= base_url() ?>assets/css/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>assets/js/azia.js"></script>
  
  
  <!-- Theme Switcher Script -->
  <script>
    (function() {
      "use strict";

      const getStoredTheme = () => {
        try {
          return localStorage.getItem("theme");
        } catch (e) {
          return null;
        }
      };
      
      const setStoredTheme = (theme) => {
        try {
          localStorage.setItem("theme", theme);
        } catch (e) {
          console.warn("Could not save theme preference");
        }
      };

      const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme === "dark" || storedTheme === "light") {
          return storedTheme;
        }
        return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      };

      const setTheme = (theme) => {
        document.documentElement.setAttribute("data-bs-theme", theme);
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));
      };

      const showActiveTheme = (theme) => {
        const themeSwitcher = document.querySelector("#bd-theme");
        if (!themeSwitcher) return;

        const activeThemeIcon = document.querySelector(".theme-icon-active");
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
        
        if (!btnToActive) return;

        const iconOfActiveBtn = btnToActive.querySelector("i")?.dataset.themeIcon;

        document.querySelectorAll("[data-bs-theme-value]").forEach((element) => {
          element.classList.remove("active");
          element.setAttribute("aria-pressed", "false");
        });

        btnToActive.classList.add("active");
        btnToActive.setAttribute("aria-pressed", "true");
        
        if (activeThemeIcon && iconOfActiveBtn) {
          activeThemeIcon.classList.remove(activeThemeIcon.dataset.themeIconActive);
          activeThemeIcon.classList.add(iconOfActiveBtn);
          activeThemeIcon.dataset.iconActive = iconOfActiveBtn;
        }
      };

      const theme = getPreferredTheme();
      setTheme(theme);

      window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
        const storedTheme = getStoredTheme();
        if (!storedTheme) {
          setTheme(getPreferredTheme());
        }
      });

      document.addEventListener("DOMContentLoaded", () => {
        showActiveTheme(getPreferredTheme());

        document.querySelectorAll("[data-bs-theme-value]").forEach((toggle) => {
          toggle.addEventListener("click", (e) => {
            e.preventDefault();
            const theme = toggle.getAttribute("data-bs-theme-value");
            setStoredTheme(theme);
            setTheme(theme);
            showActiveTheme(theme);
          });
        });
      });
    })();
  </script>
  
  <script>
    window.addEventListener('load', function() {
      document.documentElement.style.visibility = '';
      document.documentElement.style.opacity = '';
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>