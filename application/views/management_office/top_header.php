<div class="p-0">
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url() ?>management_office/dashboard"><img class="img-fluid" src="<?= base_url() ?>assets/css/images/semutgajah.png" alt="semutgajah" width="70" /></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mb-2 mb-lg-0 d-flex gap-3 me-auto justify-center">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-graph-up-arrow me-2"></i>Dashboard</a>            
          <ul class="dropdown-menu">
            <?php 
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'septian') { ?>
              <li>
                <a href="<?= base_url() ?>target_outlet/dashboard_loyalty" class="dropdown-item d-flex align-items-center" target="_blank">Dashboard RM Kal-Sul</a>
              </li>
              <?php
              }
            ?>
            <?php 
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'zul') { ?>
              <li>
                <a href="<?= base_url() ?>mti/dashboard" class="dropdown-item d-flex align-items-center" target="_blank">Dashboard MTI</a>
              </li>
              <?php
              }
            ?>                
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-box-seam me-2"></i>Inventory</a>            
          <ul class="dropdown-menu">
            <li>
              <a href="<?= base_url() ?>stok/stok_product" class="dropdown-item d-flex align-items-center" target="_blank">Report Stock Product</a>
              <a href="<?= base_url() ?>inventory/stock_akhir_doi" class="dropdown-item d-flex align-items-center" target="_blank">Report Stock DOI</a>
              <a href="<?= base_url() ?>spk/po_outstanding" class="dropdown-item d-flex align-items-center" target="_blank">Report PO Outstanding</a>
              <a href="<?= base_url() ?>inventory/laporan_po" class="dropdown-item d-flex align-items-center" target="_blank">Report PO </a>
              <a href="<?= base_url() . 'management_inventory/master_data' ?>" class="dropdown-item d-flex align-items-center">Retur Master Data</a>
              <a href="<?= base_url() . 'management_inventory/dashboard' ?>" class="dropdown-item d-flex align-items-center">Retur Versi 2</a>
              <a href="<?= base_url() . 'retur' ?>" class="dropdown-item d-flex align-items-center" target="_blank">Retur Versi 1</a>
              <?php
              if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'suffy') { ?>
              <a href="<?= base_url() . 'management_inventory/pengajuan_retur' ?>" class="dropdown-item d-flex align-items-center" target="_blank">Retur Khusus</a>
              <?php
              }
              ?>
              <a href="<?= base_url() . 'spk/dashboard' ?>" class="dropdown-item d-flex align-items-center">PO Dashboard</a>
              <?php
              if ($this->session->userdata('username') == 'melinda' || $this->session->userdata('username') == 'tria') { ?>
                <a href="<?= base_url() ?>helpdesk" class="dropdown-item d-flex align-items-center" target="_blank">Helpdesk</a>
                <a href="<?= base_url() ?>dc/dashboard" class="dropdown-item d-flex align-items-center" target="_blank">DC</a>
                <a href="<?= base_url() ?>all_po/po_monitoring" class="dropdown-item d-flex align-items-center" target="_blank">Po Monitoring</a>
                <a href="<?= base_url() ?>spk/helpdesk" class="dropdown-item d-flex align-items-center" target="_blank">Helpdesk (new)</a>
              <?php
              }
              ?>
              <a href="<?= base_url() . 'spk/lead_time' ?>" class="dropdown-item d-flex align-items-center" target="_blank">Lead Time PO</a>
              <a href="<?= base_url() . 'spk/analisa_piutang' ?>" class="dropdown-item d-flex align-items-center" target="_blank">Analisa Piutang</a>
              <a href="<?= base_url() . 'spk/po_outstanding' ?>" class="dropdown-item d-flex align-items-center" target="_blank">PO Outstanding</a>
            </li>                              
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-file-earmark-bar-graph me-2"></i>Sales</a>            
          <ul class="dropdown-menu">
            <li>
              <!-- <a href="<?= base_url() ?>portal_raw" class="dropdown-item d-flex align-items-center" target="_blank">Portal Raw Data</a> -->
              <a href="<?= base_url() ?>management_raw_data" class="dropdown-item d-flex align-items-center" target="_blank">Portal Raw Data</a>
              <a href="<?= base_url() ?>sales_omzet/omzet" class="dropdown-item d-flex align-items-center" target="_blank">Omzet DP </a>
              <a href="<?= base_url() ?>sales_omzet/omzet_dp" class="dropdown-item d-flex align-items-center" target="_blank">Omzet DP (Versi 1) </a>
              <a href="<?= base_url() ?>management_sales/sell_out_product" class="dropdown-item d-flex align-items-center">Sell out Product </a>
              <a href="<?= base_url() ?>sales_omzet/sales_outlet" class="dropdown-item d-flex align-items-center" target="_blank">Sales Outlet </a>
              <a href="<?= base_url() ?>sales_omzet/sell_out_nasional" class="dropdown-item d-flex align-items-center" target="_blank">Sell Out Nasional </a>
              <a href="<?= base_url() ?>outlet_transaksi/outlet_transaksi_ytd" class="dropdown-item d-flex align-items-center">Outlet Transaksi Ytd</a>
              <a href="<?= base_url() ?>outlet_transaksi/pengambilan" class="dropdown-item d-flex align-items-center" target="_blank">Outlet Transaksi 1X 2X 3X</a>
              <a href="<?= base_url() ?>outlet_transaksi/otsc" class="dropdown-item d-flex align-items-center" target="_blank">Outlet Exception</a>
              <a href="<?= base_url() ?>penta/log_sales" class="dropdown-item d-flex align-items-center">Penta & MPI</a>
              <a href="<?= base_url() ?>pareto" class="dropdown-item d-flex align-items-center">Pareto Account Management Area</a>
              <a href="<?= base_url() ?>pareto/rank_mti" class="dropdown-item d-flex align-items-center">Pareto MTI</a>
            </li>                              
          </ul>
        </li>
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-card-checklist me-2"></i>Claim</a>            
          <ul class="dropdown-menu">
            <li>
              <a href="<?= base_url() . 'management_claim/registrasi_program' ?>" class="dropdown-item d-flex align-items-center">Registrasi Program</a>
              <a href="<?= base_url() . 'management_claim/ajuan_claim' ?>" class="dropdown-item d-flex align-items-center">Pengajuan Claim</a>
              <a href="<?= base_url() . 'management_claim/master_data' ?>" class="dropdown-item d-flex align-items-center">Master Data</a>
              <a href="<?= base_url() . 'management_claim/ajuan_claim_nka' ?>" class="dropdown-item d-flex align-items-center">Claim NKA</a>
              <?php
                if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'adi') { ?>
                  <a href="<?= base_url() . 'management_claim/monitoring' ?>" class="dropdown-item d-flex align-items-center">Monitoring</a>
                <?php } ?>
                
              <a href="<?= base_url() . 'management_claim/report_availability' ?>" class="dropdown-item d-flex align-items-center">Report Availability</a>
            </li>                              
          </ul>
        </li>


        <?php
        if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'fenny' || $this->session->userdata('username') == 'sara' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'ilham' || $this->session->userdata('username') == 'rani') { ?>
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-calendar3-range me-2"></i>Nota Retur</a>            
          <ul class="dropdown-menu">
            <li>
              <a href="<?= base_url() ?>management_retur/dashboard" class="dropdown-item d-flex align-items-center" target="_blank">Dashboard Nota
              Retur <i>(Accounting)</i></a>
              <a href="<?= base_url() ?>management_retur/master_dbsls" class="dropdown-item d-flex align-items-center" target="_blank">Master DBSLS
                <i>(Accounting)</i></a>
              <a href="<?= base_url() ?>management_retur/ajuan_retur" class="dropdown-item d-flex align-items-center" target="_blank">Ajuan Retur
                <i>(Accounting)</i></a>
              <a href="#" class="dropdown-item d-flex align-items-center" target="_blank">Ajuan Relokasi <i>(Accounting)</i></a>
              <a href="<?= base_url() ?>management_retur/nota_retur" class="dropdown-item d-flex align-items-center" target="_blank">History Nota Retur
                <i>(Accounting)</i></a>
              <a href="<?= base_url() ?>trans/retur" class="dropdown-item d-flex align-items-center" target="_blank">Retur Versi 1
                <i>(Accounting)</i></a>
              <a href="<?= base_url() ?>management_retur/data_retur" class="dropdown-item d-flex align-items-center" target="_blank">Pengajuan Retur Vs
                Nota Retur <i>(Accounting)</i></a>
            </li>                              
          </ul>
        </li>
        <?php } ?>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-calendar-day me-2"></i>Activity</a>            
          <ul class="dropdown-menu">
            <li>
              <a href="<?= base_url() ?>absensi" class="dropdown-item d-flex align-items-center">Absensi & Ruang Meeting</a>
              <a href="<?= base_url() ?>barcode" class="dropdown-item d-flex align-items-center">Request Barcode</a>
              <a href="<?= base_url() ?>management_rpd/pengajuan" class="dropdown-item d-flex align-items-center">Rencana Perjalanan Dinas</a>
              <a href="<?= base_url() ?>kpi" class="dropdown-item d-flex align-items-center">KPI S&P</a>
              <a href="<?= base_url() ?>management_biop" class="dropdown-item d-flex align-items-center">Klaim BIOP</a>
              <a href="<?= base_url() ?>management_asset/pengajuan_asset" class="dropdown-item d-flex align-items-center">Pengajuan Asset</a>
              <a href="<?= base_url() ?>management_asset/data_asset" class="dropdown-item d-flex align-items-center">Data Asset (Finance)</a>
              <!-- <a href="<?= base_url() ?>deltomed" class="dropdown-item d-flex align-items-center">Deltomed Spreading</a>
              <a href="<?= base_url() ?>deltomed/posting" class="dropdown-item d-flex align-items-center">Daily Activity</a> -->
              <a href="<?= base_url() ?>apps/landing" class="dropdown-item d-flex align-items-center">Report MPM Apps</a>
              <a href="<?= base_url() ?>products/kenaikan_harga" class="dropdown-item d-flex align-items-center">Management Harga</a>
              <a href="<?= base_url() ?>management_karyawan" class="dropdown-item d-flex align-items-center">Personalia</a>
              <a href="<?= base_url() ?>afiliasi/monthly_planning" class="dropdown-item d-flex align-items-center">Monthly Planning</a>
              <a href="<?= base_url() ?>afiliasi" class="dropdown-item d-flex align-items-center">Checklist Activity</a>
              <a href="<?= base_url() ?>step" class="dropdown-item d-flex align-items-center">Dashboard Step Counter</a>
            </li>                              
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-sliders me-2"></i>Others</a>            
          <ul class="dropdown-menu">
            <li>
              <a href="<?= base_url() ?>absensi" class="dropdown-item d-flex align-items-center">Absensi & Ruang Meeting</a>
              <a href="<?= base_url() ?>dashboard_dummy" class="dropdown-item d-flex align-items-center" target="_blank">Website MPM Old Template</a>
              <?php
              if ($this->session->userdata('username') == 'nanita' || $this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'hendra') { ?>
                <!-- <a href="<?= base_url() ?>all_transaction/open_credit_limit" class="dropdown-item d-flex align-items-center" target="_blank">Open Credit Limit <i>(Finance)</i></a> -->
                <a href="<?= base_url() ?>finance/list_data" class="dropdown-item d-flex align-items-center" target="_blank">Open Credit Limit <i>(Finance)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'milla' || $this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'tria' || $this->session->userdata('username') == 'fakhrul') { ?>
                <a href="<?= base_url() ?>master_product/product" class="dropdown-item d-flex align-items-center" target="_blank">Master Product</a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'fakhrul' || $this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'tria') { ?>
                <a href="<?= base_url() ?>management_bonus/master_data" class="dropdown-item d-flex align-items-center" target="_blank">Master Bonus <i>(SupplyChain)</i></a>
                <a href="<?= base_url() ?>management_bonus/tracking" class="dropdown-item d-flex align-items-center" target="_blank">Tracking Bonus <i>(SupplyChain)</i></a>
                <a href="<?= base_url() ?>transaction/list_order" class="dropdown-item d-flex align-items-center" target="_blank">List Order <i>(SupplyChain)</i></a>
                <a href="<?= base_url() ?>all_po/po_monitoring" class="dropdown-item d-flex align-items-center" target="_blank">PO Monitoring <i>(SupplyChain)</i></a>
                <a href="<?= base_url() ?>dc/dashboard" class="dropdown-item d-flex align-items-center" target="_blank">DC <i>(SupplyChain)</i></a>
              <?php
              }
              ?>
              
              <?php
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'septian' || $this->session->userdata('username') == 'milla' || $this->session->userdata('username') == 'hendri' || $this->session->userdata('username') == 'rifqi') { ?>
                <a href="<?= base_url() ?>management_raw" class="dropdown-item d-flex align-items-center" target="_blank">Management Raw Area <i>(RM)</i></a>
                <a href="<?= base_url() ?>bridging" class="dropdown-item d-flex align-items-center">Upload Data Bridging</a>
                <a href="<?= base_url() ?>kalimantan/po" class="dropdown-item d-flex align-items-center" target="_blank">Dashboard PO <i>(RM)</i></a>
                <a href="<?= base_url() ?>kalimantan/ajuan_retur" class="dropdown-item d-flex align-items-center" target="_blank">Dashboard Retur <i>(RM)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'sadmin' || $this->session->userdata('username') == 'dewi' || $this->session->userdata('username') == 'adinda' || $this->session->userdata('username') == 'angga') { ?>
                <a href="<?= base_url() ?>mes" class="dropdown-item d-flex align-items-center" target="_blank">MES <i>(Eccommerce)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'felix') { ?>
                <a href="<?= base_url() ?>monitor" class="dropdown-item d-flex align-items-center" target="_blank">Monitor Closing Deltomed <i>(System)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'sampurno' || $this->session->userdata('username') == 'sampurno') { ?>
                <a href="<?= base_url() ?>monitor" class="dropdown-item d-flex align-items-center" target="_blank">Dashboard MTI <i>(Kam)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'suffy') { ?>
                <a href="<?= base_url() ?>management_claim/buletin_program" class="dropdown-item d-flex align-items-center" target="_blank">Claim Dashboard <i>(Admin)</i></a>
                <a href="<?= base_url() ?>management_claim/dashboard" class="dropdown-item d-flex align-items-center" target="_blank">Claim Dashboard <i>(Admin)</i></a>
                <a href="<?= base_url() ?>management_claim/site" class="dropdown-item d-flex align-items-center" target="_blank">Registrasi Site <i>(Admin)</i></a>
                <a href="<?= base_url() ?>management_claim/registrasi_program" class="dropdown-item d-flex align-items-center" target="_blank">Registrasi Program <i>(Admin)</i></a>
                <a href="<?= base_url() ?>management_claim/ajuan_claim" class="dropdown-item d-flex align-items-center" target="_blank">Ajuan Program <i>(Admin)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'tius' || $this->session->userdata('username') == 'erlandy' || $this->session->userdata('username') == 'milla') { ?>
                <a href="<?= base_url() ?>surat_jalan" class="dropdown-item d-flex align-items-center" target="_blank">Surat Jalan <i>(Finance)</i></a>
              <?php
              }
              ?>
              <?php
              if ($this->session->userdata('username') == 'dewi' || $this->session->userdata('username') == 'zul' || $this->session->userdata('username') == 'suffy') { ?>
                <a href="<?= base_url() ?>request/history" class="dropdown-item d-flex align-items-center" target="_blank">Perubahan Tipe Outlet <i>(KAM)</i></a>
              <?php
              }
              ?>
            </li>                              
          </ul>
        </li>


        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle me-2"></i>Profile (<?php echo $this->session->userdata('username'); ?>)</a>            
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
</div>