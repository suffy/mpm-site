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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <script>
      // Immediately set the theme before the page renders
      (function() {
        const getStoredTheme = () => localStorage.getItem("theme");
        const getPreferredTheme = () => {
          const storedTheme = getStoredTheme();
          if (storedTheme) {
            return storedTheme;
          }
          return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        };
        
        // Apply theme immediately
        const theme = getPreferredTheme();
        document.documentElement.setAttribute("data-bs-theme", theme);
      })();
    </script>
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
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-card-checklist me-2"></i>Claim</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="dropdown-item d-flex align-items-center">Pengajuan Claim</a>
                  <!-- <a href="<?= base_url().'management_claim/monitoring' ?>" class="dropdown-item d-flex align-items-center">Monitoring</a> -->
                </li>                              
              </ul>
            </li>


            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-calendar-day me-2"></i>Activity</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url() ?>management_rpd/pengajuan" class="dropdown-item d-flex align-items-center">Rencana Perjalanan Dinas</a>
                  <a href="<?= base_url() ?>apps/landing" class="dropdown-item d-flex align-items-center">Report MPM Apps</a>
                </li>                              
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-sliders me-2"></i>Others</a>            
              <ul class="dropdown-menu">
                <li>
                  <a href="<?= base_url() ?>dashboard_dummy" class="dropdown-item d-flex align-items-center" target="_blank">Website MPM Old Template</a>
                  <?php
                  if ($this->session->userdata('username') == 'april_deltomed') { ?>
                    <a href="<?= base_url() ?>transaction/list_order" class="dropdown-item d-flex align-items-center" target="_blank">List Order <i>(SupplyChain)</i></a>
                    <?php
                  }
                  ?>
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