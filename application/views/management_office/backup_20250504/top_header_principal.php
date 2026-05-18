<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="author" content="BootstrapDash">

    <title><?= $title ?></title>

    <!-- vendor css -->
    <link href="<?= base_url() ?>assets/css/lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/lib/typicons.font/typicons.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/azia.css">

    <!-- datatable -->
    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>

    <!-- fontawesome -->
    <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/fontawesome.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/brands.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/fontawesome-free-6.5.1-web/css/solid.css" rel="stylesheet">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  </head>
  <body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand" href="#"><img class="img-fluid" src="<?= base_url() ?>assets/css/images/semutgajah.png" alt="semutgajah" width="70" /></a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mb-2 mb-lg-0 d-flex gap-3 me-auto justify-center">
            <li class="nav-item dropdown">
              <a href="<?= base_url() ?>management_office" class="nav-link" type="button" aria-expanded="false"><i class="bi bi-graph-up-arrow me-2"></i>Dashboard</a>           
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-box-seam me-2"></i>Inventory</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url() ?>stok/stok_product" class="dropdown-item d-flex align-items-center" target="_blank">Report Stock Product</a>
                  <a href="<?= base_url() ?>inventory/stock_akhir_doi" class="dropdown-item d-flex align-items-center" target="_blank">Report Stock DOI</a>
                  <a href="<?= base_url() ?>inventory/po_outstanding" class="dropdown-item d-flex align-items-center" target="_blank">Report PO Outstanding</a>
                  <a href="<?= base_url() ?>inventory/laporan_po" class="dropdown-item d-flex align-items-center" target="_blank">Report PO  </a>
                  <a href="#" class="dropdown-item d-flex align-items-center">Dashboard PO (*)</a>
                  <a href="<?= base_url().'management_inventory/dashboard' ?>" class="dropdown-item d-flex align-items-center">Retur Versi 2</a>
                  <!-- <a href="#" class="dropdown-item d-flex align-items-center">Retur Versi 2 (coming soon)</a> -->
                  <a href="<?= base_url().'retur' ?>" class="dropdown-item d-flex align-items-center" target="_blank">Retur Versi 1</a>
                  <a href="#" class="dropdown-item d-flex align-items-center">Dashboard Tracking Bonus (*)</a>
                </li>                              
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-file-earmark-bar-graph me-2"></i>Sales</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url() ?>portal_raw" class="dropdown-item d-flex align-items-center" target="_blank">Portal Raw Data</a>
                  <a href="<?= base_url() ?>sales_omzet/omzet" class="dropdown-item d-flex align-items-center" target="_blank">Omzet DP  </a>
                  <a href="<?= base_url() ?>sales_omzet/omzet_dp" class="dropdown-item d-flex align-items-center" target="_blank">Omzet DP (Versi 1) </a>
                  <a href="<?= base_url() ?>sales_omzet/sell_out_product" class="dropdown-item d-flex align-items-center" target="_blank">Sell out Product  </a>
                  <a href="<?= base_url() ?>sales_omzet/sales_outlet" class="dropdown-item d-flex align-items-center" target="_blank">Sales Outlet  </a>
                  <a href="<?= base_url() ?>sales_omzet/sell_out_nasional" class="dropdown-item d-flex align-items-center" target="_blank">Sell Out Nasional  </a>
                  <a href="<?= base_url() ?>outlet_transaksi/outlet_transaksi_ytd" class="dropdown-item d-flex align-items-center" target="_blank">Outlet Transaksi Ytd</a>
                  <a href="<?= base_url() ?>outlet_transaksi/pengambilan" class="dropdown-item d-flex align-items-center" target="_blank">Outlet Transaksi 1X 2X 3X</a>
                  <a href="<?= base_url() ?>outlet_transaksi/otsc" class="dropdown-item d-flex align-items-center" target="_blank">Outlet Exception</a>
                  <?php if ($this->session->userdata('username') == 'dian' || $this->session->userdata('username') == 'giovani' || $this->session->userdata('username') == 'denny'){ ?>
                  <a href="<?= base_url() ?>penta/log_sales" class="dropdown-item d-flex align-items-center">Penta & MPI</a>
                  <?php } ?>
                </li>                              
              </ul>
            </li>
            

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-card-checklist me-2"></i>Claim</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url().'management_claim/buletin_program' ?>" class="dropdown-item d-flex align-items-center">Buletin Program</a> 
                  <a href="<?= base_url().'management_claim/dashboard' ?>" class="dropdown-item d-flex align-items-center">Dashboard</a>
                  <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="dropdown-item d-flex align-items-center">Pengajuan Claim</a>
                  <a href="<?= base_url().'management_claim/ajuan_claim_mti' ?>" class="dropdown-item d-flex align-items-center">Pengajuan Claim MPI</a>
                </li>                              
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-sliders me-2"></i>Others</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url() ?>dashboard_dummy" class="dropdown-item d-flex align-items-center" target="_blank">Website MPM Old Template</a>
                </li>                              
              </ul>
            </li>


            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle me-2"></i>Profile</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url() ?>profile/account" class="dropdown-item d-flex align-items-center" target="_blank">My Profile</a>
                  <a href="<?= base_url() ?>management_profile/signature" class="dropdown-item d-flex align-items-center" target="_blank">Signature</a>
                  <a href="<?= base_url() ?>login/logout" class="dropdown-item d-flex align-items-center">Logout</a>
                </li>                              
              </ul>
            </li>

            <li class="nav-item dropdown">
              <button
                class="btn nav-link dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                id="bd-theme"
              >
                <i
                  class="bi bi-sun-fill theme-icon-active"
                  data-theme-icon-active="bi-sun-fill"
                  id="bd-theme-text"
                ></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <button
                    class="dropdown-item d-flex align-items-center"
                    type="button"
                    data-bs-theme-value="light"
                    id="bd-theme"
                  >
                    <i
                      class="bi bi-sun-fill me-2 opacity-50"
                      data-theme-icon="bi-sun-fill"
                      id="bd-theme-text"
                    ></i
                    >Light
                  </button>
                </li>
                <li>
                  <button
                    class="dropdown-item d-flex align-items-center"
                    type="button"
                    data-bs-theme-value="dark"
                    id="bd-theme"
                  >
                    <i
                      class="bi bi-moon-fill me-2 opacity-50"
                      data-theme-icon="bi-moon-fill"
                      id="bd-theme-text"
                    ></i
                    >Dark Mode (Development)
                  </button>
                </li>
                <li>
                  <button
                    class="dropdown-item d-flex align-items-center"
                    type="button"
                    data-bs-theme-value="auto"
                    id="bd-theme"
                  >
                    <i
                      class="bi bi-circle-half me-2 opacity-50"
                      data-theme-icon="bi-circle-half"
                      id="bd-theme-text"
                    ></i
                    >Auto
                  </button>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>