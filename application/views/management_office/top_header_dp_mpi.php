<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
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
              <a href="<?= base_url().'management_inventory/dashboard' ?>" class="dropdown-item d-flex align-items-center">Retur Versi 2</a>
            <a href="<?= base_url().'retur' ?>" class="dropdown-item d-flex align-items-center" target="_blank">Retur Versi 1</a>
            </li>                              
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-card-checklist me-2"></i>Claim</a>            
          <ul class="dropdown-menu">
            <li>
              <a href="<?= base_url() . 'management_claim/ajuan_claim_nka' ?>" class="dropdown-item d-flex align-items-center">Claim NKA</a>
              <a href="<?= base_url() . 'management_claim/ajuan_claim' ?>" class="dropdown-item d-flex align-items-center">Claim GT</a>
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
          <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle me-2"></i>Profile (<?= $this->session->userdata('username'); ?>)</a>            
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