<style>
  /* CSS untuk background wallpaper */
  .dashboard-with-bg {
    position: relative;
    min-height: 100vh;
  }

  .dashboard-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: -1;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: scroll;
  }

  .dashboard-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
  }

  .dashboard-content {
    position: relative;
    z-index: 1;
  }

  .dashboard-hero {
    position: relative;
    border-radius: 10px;
    margin-bottom: 30px;
    color: white;
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    overflow: hidden;
  }

  .hero-video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
  }

  .hero-video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.75), rgba(33, 37, 41, 0.75));
    z-index: 1;
  }

  .hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 30px;
  }

  .dashboard-hero .container-fluid,
  .dashboard-hero .row {
    width: 100%;
  }

  .hero-video-background {
    filter: brightness(0.7);
  }

  /* Search Bar Styling */
  .search-section {
    margin-bottom: 20px;
    padding: 0 10px;
  }

  .search-wrapper {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 5px 15px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
  }

  [data-bs-theme="dark"] .search-wrapper {
    background: rgba(33, 37, 41, 0.95);
    border-color: rgba(255,255,255,0.1);
  }

  .search-wrapper:focus-within {
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.25);
    border-color: var(--bs-primary);
  }

  .search-icon {
    color: var(--bs-primary);
    font-size: 1rem;
    margin-right: 10px;
  }

  .search-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 0;
    font-size: 0.9rem;
    color: var(--bs-body-color);
    outline: none;
  }

  .search-input::placeholder {
    color: var(--bs-secondary-color);
    font-size: 0.85rem;
  }

  .search-clear-btn {
    background: transparent;
    border: none;
    color: var(--bs-secondary-color);
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .search-clear-btn:hover {
    color: var(--bs-danger);
    background: rgba(var(--bs-danger-rgb), 0.1);
  }

  .search-info {
    font-size: 0.7rem;
    color: var(--bs-secondary-color);
    margin-top: 8px;
    padding-left: 15px;
  }

  /* Two Column Layout */
  .two-column-section {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    flex-wrap: wrap;
  }

  .left-column {
    flex: 2;
    min-width: 280px;
  }

  .right-column {
    flex: 1;
    min-width: 260px;
  }

  /* Card untuk daily activity dengan scroll vertikal */
  .daily-activity-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  [data-bs-theme="dark"] .daily-activity-card {
    background: rgba(33, 37, 41, 0.95);
    border-color: rgba(255,255,255,0.1);
  }

  .daily-activity-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--bs-primary);
    flex-shrink: 0;
  }

  .daily-activity-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .daily-activity-header-left i {
    font-size: 1.3rem;
    color: var(--bs-primary);
  }

  .daily-activity-header-left h4 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: var(--bs-body-color);
  }

  .daily-activity-date {
    font-size: 0.7rem;
    color: var(--bs-secondary-color);
  }

  .daily-activity-header .btn-view-all-sm {
    font-size: 0.7rem;
    padding: 4px 10px;
    background-color: var(--bs-dark-border-subtle);
    border-radius: 20px;
    color: var(--bs-body-color);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
  }

  .daily-activity-header .btn-view-all-sm:hover {
    background-color: var(--bs-primary);
    color: white;
    gap: 8px;
  }

  /* Container scroll vertikal */
  .activity-list-container {
    flex: 1;
    overflow-y: auto;
    max-height: 300px;
    scrollbar-width: thin;
    scrollbar-color: var(--bs-dark-border-subtle) rgba(0,0,0,0.1);
  }

  .activity-list-container::-webkit-scrollbar {
    width: 5px;
  }

  .activity-list-container::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.1);
    border-radius: 10px;
  }

  .activity-list-container::-webkit-scrollbar-thumb {
    background: var(--bs-dark-border-subtle);
    border-radius: 10px;
  }

  .activity-list-container::-webkit-scrollbar-thumb:hover {
    background: var(--bs-dark-text-emphasis);
  }

  .activity-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-right: 5px;
  }

  .activity-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    border-radius: 8px;
    transition: all 0.2s ease;
    background: rgba(var(--bs-primary-rgb), 0.03);
  }

  .activity-item:hover {
    background: rgba(var(--bs-primary-rgb), 0.08);
    transform: translateX(3px);
  }

  .activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(var(--bs-primary-rgb), 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--bs-primary);
    flex-shrink: 0;
  }

  .activity-icon i {
    font-size: 1rem;
  }

  .activity-info {
    flex: 1;
    min-width: 0;
  }

  .activity-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--bs-body-color);
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .activity-time {
    font-size: 0.65rem;
    color: var(--bs-secondary-color);
  }

  .activity-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.6rem;
    font-weight: 600;
    flex-shrink: 0;
    white-space: nowrap;
  }

  .badge-completed {
    background: #d4edda;
    color: #155724;
  }

  .badge-pending {
    background: #fff3cd;
    color: #856404;
  }

  .badge-upcoming {
    background: #cce5ff;
    color: #004085;
  }

  [data-bs-theme="dark"] .badge-completed {
    background: #1e4620;
    color: #a5d6a7;
  }

  [data-bs-theme="dark"] .badge-pending {
    background: #665c2c;
    color: #ffe69b;
  }

  [data-bs-theme="dark"] .badge-upcoming {
    background: #004085;
    color: #cce5ff;
  }

  /* Horizontal Scroll Section untuk left column */
  .horizontal-scroll-section {
    position: relative;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 0 10px;
  }

  .section-header h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--bs-dark-text-emphasis);
    margin: 0;
    background-color: var(--bs-primary-bg-subtle);
    padding: 5px 12px;
    border-radius: 5px;
  }

  .section-header .btn-view-all {
    margin: 0;
  }

  .section-header .btn-view-all a {
    font-size: 0.85rem;
    padding: 5px 12px;
    background-color: var(--bs-dark-border-subtle);
    border-radius: 20px;
    color: var(--bs-body-color);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
  }

  .section-header .btn-view-all a:hover {
    background-color: var(--bs-primary);
    color: white;
    gap: 8px;
  }

  .scroll-wrapper-container {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .scroll-nav-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--bs-dark-border-subtle);
    color: var(--bs-body-color);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }

  .scroll-nav-btn:hover {
    background: var(--bs-primary);
    color: white;
    transform: scale(1.1);
  }

  .scroll-nav-btn:active {
    transform: scale(0.95);
  }

  .scroll-container {
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    padding-bottom: 10px;
    flex: 1;
    scrollbar-width: thin;
    scrollbar-color: var(--bs-dark-border-subtle) rgba(0,0,0,0.1);
  }

  .scroll-container::-webkit-scrollbar {
    height: 5px;
  }

  .scroll-container::-webkit-scrollbar-track {
    background: rgba(0,0,0,0.1);
    border-radius: 10px;
  }

  .scroll-container::-webkit-scrollbar-thumb {
    background: var(--bs-dark-border-subtle);
    border-radius: 10px;
  }

  .scroll-container::-webkit-scrollbar-thumb:hover {
    background: var(--bs-dark-text-emphasis);
  }

  .scroll-wrapper {
    display: inline-flex;
    gap: 15px;
    padding: 5px 10px;
    justify-content: flex-start;
    min-width: 100%;
  }

  /* Compact Card Styling */
  .horizontal-card {
    width: 280px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    white-space: normal;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: inline-block;
    vertical-align: top;
    border: 1px solid rgba(255,255,255,0.2);
  }

  .horizontal-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
  }

  [data-bs-theme="dark"] .horizontal-card {
    background: rgba(33, 37, 41, 0.95);
    border-color: rgba(255,255,255,0.1);
  }

  /* Empty State Styling */
  .empty-state {
    width: 100%;
    text-align: center;
    padding: 40px 20px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.2);
  }

  .empty-state i {
    font-size: 3rem;
    color: var(--bs-primary);
    opacity: 0.5;
    margin-bottom: 15px;
  }

  .empty-state h5 {
    color: var(--bs-body-color);
    margin-bottom: 8px;
  }

  .empty-state p {
    color: var(--bs-secondary-color);
    font-size: 0.85rem;
    margin-bottom: 15px;
  }

  .empty-state .btn-sm {
    display: inline-block;
  }

  [data-bs-theme="dark"] .empty-state {
    background: rgba(33, 37, 41, 0.95);
  }

  /* Team Member Card Styling */
  .team-member-horizontal {
    text-align: center;
  }

  .member-avatar-large {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--bs-primary), var(--bs-success));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
  }

  .member-name {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 8px 0 2px;
    color: var(--bs-body-color);
  }

  /* Attendance Stats Styling */
  .attendance-stats {
    font-size: 0.7rem;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #eee;
    display: flex;
    flex-direction: column;
    gap: 5px;
  }

  [data-bs-theme="dark"] .attendance-stats {
    border-top-color: #404040;
  }

  .attendance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2px 0;
  }

  .attendance-label {
    font-weight: 500;
    color: var(--bs-body-color);
    opacity: 0.7;
    font-size: 0.65rem;
  }

  .attendance-label i {
    margin-right: 4px;
    width: 12px;
    font-size: 0.65rem;
    color: var(--bs-primary);
  }

  .attendance-value {
    font-weight: 600;
    color: var(--bs-body-color);
    background: rgba(var(--bs-primary-rgb), 0.1);
    padding: 1px 6px;
    border-radius: 12px;
    font-size: 0.65rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  /* Additional CSS for location items */
  .attendance-item.location-item {
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .attendance-item.location-item:hover {
    background: rgba(var(--bs-primary-rgb), 0.05);
    border-radius: 6px;
    padding-left: 5px;
  }

  .location-value {
    transition: all 0.2s ease;
  }

  .location-value:hover {
    background: var(--bs-primary);
    color: white !important;
    transform: scale(1.02);
  }

  .location-value:hover i.fa-info-circle {
    opacity: 1;
  }

  /* Tasks Stats */
  .tasks-stats {
    font-size: 0.65rem;
    margin-top: 6px;
    padding: 4px 6px;
    background: rgba(var(--bs-primary-rgb), 0.05);
    border-radius: 6px;
    display: flex;
    justify-content: space-between;
  }

  .tasks-stats span i {
    margin-right: 3px;
    font-size: 0.6rem;
    color: var(--bs-primary);
  }

  .status-late {
    background: #fff3cd;
    color: #856404;
    padding: 0 6px;
    border-radius: 10px;
    font-size: 0.6rem;
  }

  [data-bs-theme="dark"] .status-late {
    background: #665c2c;
    color: #ffe69b;
  }

  /* Report Card Styling */
  .report-card-horizontal {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .report-icon-large {
    width: 45px;
    height: 45px;
    background: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
  }

  .report-icon-large i {
    font-size: 1.5rem;
    color: var(--bs-primary);
  }

  .report-title {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--bs-body-color);
  }

  .report-description {
    font-size: 0.7rem;
    color: #6c757d;
    margin-bottom: 8px;
    line-height: 1.3;
  }

  [data-bs-theme="dark"] .report-description {
    color: #adb5bd;
  }

  .report-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 8px;
    border-top: 1px solid #eee;
  }

  [data-bs-theme="dark"] .report-meta {
    border-top-color: #404040;
  }

  .report-date {
    font-size: 0.65rem;
    color: #6c757d;
  }

  .report-status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.6rem;
    font-weight: 600;
  }

  .status-completed {
    background: #d4edda;
    color: #155724;
  }

  .status-pending {
    background: #fff3cd;
    color: #856404;
  }

  [data-bs-theme="dark"] .status-completed {
    background: #1e4620;
    color: #a5d6a7;
  }

  [data-bs-theme="dark"] .status-pending {
    background: #665c2c;
    color: #ffe69b;
  }

  .btn-submit {
    color: white;
    background-color: var(--bs-primary);
    border-radius: 6px;
    border: none;
    font-size: 0.7rem;
    padding: 4px 8px;
    margin-top: 8px;
  }

  .btn-submit:hover {
    background-color: var(--bs-success);
    color: white;
  }

  /* Search Highlight */
  .search-highlight {
    background-color: rgba(var(--bs-primary-rgb), 0.2);
    border-radius: 4px;
    padding: 0 2px;
  }

  /* Two Search Bars Layout */
  .search-bars-container {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .search-bar-item {
    flex: 1;
    min-width: 250px;
  }

  .search-bar-label {
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--bs-secondary-color);
    margin-bottom: 5px;
    padding-left: 15px;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .two-column-section {
      flex-direction: column;
    }
    
    .left-column, .right-column {
      width: 100%;
    }
    
    .horizontal-card {
      width: 260px;
      padding: 10px;
    }
    
    .dashboard-hero {
      min-height: calc(100vh - 60px);
    }
    
    .hero-content {
      padding: 20px;
    }
    
    .dashboard-hero h1 {
      font-size: 1.3rem;
    }
    
    .dashboard-hero .display-4 {
      font-size: 1.8rem;
    }
    
    .section-header h3 {
      font-size: 1rem;
      padding: 4px 10px;
    }
    
    .section-header .btn-view-all a {
      font-size: 0.7rem;
      padding: 4px 10px;
    }
    
    .scroll-nav-btn {
      width: 28px;
      height: 28px;
    }
    
    .member-avatar-large {
      width: 50px;
      height: 50px;
      font-size: 1.2rem;
    }
    
    .member-name {
      font-size: 0.85rem;
    }
    
    .activity-list-container {
      max-height: 320px;
    }
    
    .search-wrapper {
      padding: 5px 12px;
    }
    
    .search-input {
      font-size: 0.85rem;
    }
    
    .search-bars-container {
      flex-direction: column;
      gap: 15px;
    }
  }

  @media (max-width: 576px) {
    .scroll-nav-btn {
      display: none;
    }
    
    .section-header {
      flex-wrap: wrap;
      gap: 8px;
    }
  }
</style>

<div class="dashboard-with-bg">
  <div class="dashboard-background"></div>
    
  <div class="dashboard-content">
    <div class="dashboard-hero">
      <video class="hero-video-background" autoplay muted loop playsinline>
        <source src="<?php echo base_url() ?>assets/video/mpm.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="hero-video-overlay"></div>
            
      <div class="hero-content">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-md-7">
              <h1 class="display-4 fw-bold mb-3">Welcome Back, <?php echo $this->session->userdata('username') ? $this->session->userdata('username') : 'User'; ?>! 👋</h1>
              <a href="#sections" class="btn btn-light btn-sm px-3">Explore your Dashboard <i class="fas fa-arrow-down ms-2"></i></a>
            </div>
            <div class="col-md-5">
            </div>
          </div>
        </div>
      </div>
    </div>
        
    <div id="sections">
      <!-- Two Separate Search Bars -->
      <div class="container-fluid">
        <div class="search-bars-container">
          <!-- Search Bar 1: For Team Members & Daily Activity -->
          <div class="search-bar-item">
            <div class="search-bar-label">
              <i class="fas fa-users me-1"></i> Search Team Members & Activities
            </div>
            <div class="search-wrapper">
              <i class="fas fa-search search-icon"></i>
              <input type="text" id="searchTeamActivity" class="search-input" placeholder="Search by username..." autocomplete="off">
              <button id="clearTeamActivityBtn" class="search-clear-btn" style="display: none;">
                <i class="fas fa-times-circle"></i>
              </button>
            </div>
            <div class="search-info" id="teamActivityInfo"></div>
          </div>

          <!-- Search Bar 2: For Top Reports -->
          <div class="search-bar-item">
            <div class="search-bar-label">
              <i class="fas fa-chart-line me-1"></i> Search Reports
            </div>
            <div class="search-wrapper">
              <i class="fas fa-search search-icon"></i>
              <input type="text" id="searchReports" class="search-input" placeholder="Search by report title..." autocomplete="off">
              <button id="clearReportsBtn" class="search-clear-btn" style="display: none;">
                <i class="fas fa-times-circle"></i>
              </button>
            </div>
            <div class="search-info" id="reportsInfo"></div>
          </div>
        </div>
      </div>

      <!-- Two Column Section: Your Team Members + Daily Activity -->
      <div class="container-fluid">
        <div class="two-column-section">
          <!-- Left Column: Your Team Members -->
          <div class="left-column">
            <div class="horizontal-scroll-section">
              <div class="section-header">
                <h3><i class="fas fa-users me-2"></i> Your Team Members</h3>
                <div class="btn-view-all">
                  <a href="<?php echo base_url('management_office/all_team_member'); ?>">
                    View All <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
              <div class="scroll-wrapper-container">
                <button class="scroll-nav-btn scroll-left" data-target="team-scroll">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <div class="scroll-container" id="team-scroll">
                  <div class="scroll-wrapper" id="teamMembersWrapper">
                    <?php 
                    if(isset($get_absensi) && $get_absensi->num_rows() > 0): 
                      foreach($get_absensi->result() as $a): 
                    ?>
                    <div class="horizontal-card" data-username="<?php echo strtolower($a->username); ?>">
                      <div class="team-member-horizontal">
                        <div class="member-avatar-large">
                          <?php echo strtoupper(substr($a->username, 0, 2)); ?>
                        </div>
                        <div class="member-name"><?php echo $a->username; ?></div>
                        
                        <div class="attendance-stats">
                          <div class="attendance-item">
                            <span class="attendance-label">
                              <i class="fas fa-sign-in-alt"></i> Clock In
                            </span>
                            <span class="attendance-value">
                              <?php echo $a->actual_masuk ? date('H:i', strtotime($a->actual_masuk)) : '--:--'; ?>
                            </span>
                          </div>
                          
                          <?php if(!empty($a->latitude_masuk) && !empty($a->longitude_masuk)): ?>
                          <div class="attendance-item location-item">
                            <span class="attendance-label">
                              <i class="fas fa-map-marker-alt"></i> Location In
                            </span>
                            <span class="attendance-value location-value" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#attendanceModal"
                                  data-type="masuk"
                                  data-username="<?php echo $a->username; ?>"
                                  data-lat="<?php echo $a->latitude_masuk; ?>"
                                  data-lng="<?php echo $a->longitude_masuk; ?>"
                                  data-address="<?php echo htmlspecialchars(isset($a->address_masuk) ? $a->address_masuk : '-', ENT_QUOTES); ?>"
                                  data-image="<?php echo isset($a->image_masuk) ? $a->image_masuk : ''; ?>"
                                  data-time="<?php echo $a->actual_masuk ? date('H:i:s', strtotime($a->actual_masuk)) : '--:--'; ?>"
                                  style="cursor: pointer;">
                              <i class="fas fa-location-dot"></i>
                              <?php 
                              $lat_short = strlen($a->latitude_masuk) > 8 ? substr($a->latitude_masuk, 0, 8) . '...' : $a->latitude_masuk;
                              $lng_short = strlen($a->longitude_masuk) > 8 ? substr($a->longitude_masuk, 0, 8) . '...' : $a->longitude_masuk;
                              echo $lat_short . ', ' . $lng_short;
                              ?>
                              <i class="fas fa-info-circle ms-1" style="font-size: 0.6rem;"></i>
                            </span>
                          </div>
                          <?php else: ?>
                          <div class="attendance-item">
                            <span class="attendance-label">
                              <i class="fas fa-map-marker-alt"></i> Location In
                            </span>
                            <span class="attendance-value">
                              <i class="fas fa-minus-circle"></i> --
                            </span>
                          </div>
                          <?php endif; ?>

                          <div class="attendance-item">
                            <span class="attendance-label">
                              <i class="fas fa-sign-out-alt"></i> Clock Out
                            </span>
                            <span class="attendance-value">
                              <?php echo $a->actual_keluar ? date('H:i', strtotime($a->actual_keluar)) : '--:--'; ?>
                            </span>
                          </div>

                          <?php if(!empty($a->latitude_keluar) && !empty($a->longitude_keluar)): ?>
                          <div class="attendance-item location-item">
                            <span class="attendance-label">
                              <i class="fas fa-map-marker-alt"></i> Location Out
                            </span>
                            <span class="attendance-value location-value" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#attendanceModal"
                                  data-type="keluar"
                                  data-username="<?php echo $a->username; ?>"
                                  data-lat="<?php echo $a->latitude_keluar; ?>"
                                  data-lng="<?php echo $a->longitude_keluar; ?>"
                                  data-address="<?php echo htmlspecialchars(isset($a->address_keluar) ? $a->address_keluar : '-', ENT_QUOTES); ?>"
                                  data-image="<?php echo isset($a->image_keluar) ? $a->image_keluar : ''; ?>"
                                  data-time="<?php echo $a->actual_keluar ? date('H:i:s', strtotime($a->actual_keluar)) : '--:--'; ?>"
                                  style="cursor: pointer;">
                              <i class="fas fa-location-dot"></i>
                              <?php 
                              $lat_short = strlen($a->latitude_keluar) > 8 ? substr($a->latitude_keluar, 0, 8) . '...' : $a->latitude_keluar;
                              $lng_short = strlen($a->longitude_keluar) > 8 ? substr($a->longitude_keluar, 0, 8) . '...' : $a->longitude_keluar;
                              echo $lat_short . ', ' . $lng_short;
                              ?>
                              <i class="fas fa-info-circle ms-1" style="font-size: 0.6rem;"></i>
                            </span>
                          </div>
                          <?php else: ?>
                          <div class="attendance-item">
                            <span class="attendance-label">
                              <i class="fas fa-map-marker-alt"></i> Location Out
                            </span>
                            <span class="attendance-value">
                              <i class="fas fa-minus-circle"></i> --
                            </span>
                          </div>
                          <?php endif; ?>
                        </div>
                        
                        <div class="tasks-stats">
                          <span><i class="fas fa-tasks"></i> <?php echo isset($a->count) ? $a->count : '0'; ?> activity</span>
                          <?php if(isset($a->flag_terlambat) && $a->flag_terlambat == 1): ?>
                          <span class="status-late">
                            <i class="fas fa-exclamation-triangle"></i> Late
                          </span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <?php 
                      endforeach;
                    else: 
                    ?>
                    <div class="empty-state" style="width: 100%; min-width: 300px;">
                      <i class="fas fa-user-clock fa-3x"></i>
                      <h5>No Team Members Data</h5>
                      <p>User anda tidak memiliki team member</p>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
                <button class="scroll-nav-btn scroll-right" data-target="team-scroll">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
          
          <!-- Right Column: Daily Activity dengan Scroll Vertikal -->
          <div class="right-column">
            <div class="daily-activity-card">
              <div class="daily-activity-header">
                <div class="daily-activity-header-left">
                  <i class="fas fa-calendar-day"></i>
                  <h4>Daily Activity</h4>
                </div>
                <div>
                  <span class="daily-activity-date"><?php echo $today; ?></span>
                  <a href="<?php echo base_url('management_office/all_team_member'); ?>" class="btn-view-all-sm ms-2">
                    View All <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
              
              <!-- Container dengan scroll vertikal -->
              <div class="activity-list-container">
                <div class="activity-list" id="activityList">
                  <?php foreach($get_activity->result() as $a): ?>
                  <div class="activity-item" data-username="<?php echo strtolower($a->username); ?>">
                    <div class="activity-info">
                      <div class="activity-title"><?php echo $a->username; ?></div>
                      <div class="activity-time"><?php echo $a->result; ?></div>
                      <div class="activity-time"><?php echo $a->district; ?></div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        
      <!-- Section 2: Reports - Horizontal Scroll -->
      <div class="container-fluid">
        <div class="horizontal-scroll-section" id="reportsSection">
          <div class="section-header">
            <h3><i class="fas fa-chart-line me-2"></i> Your Top Reports</h3>
            <div class="btn-view-all">
              <a href="<?php echo base_url('management_office/reports'); ?>">
                View All <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="scroll-wrapper-container">
            <button class="scroll-nav-btn scroll-left" data-target="reports-scroll">
              <i class="fas fa-chevron-left"></i>
            </button>
            <div class="scroll-container" id="reports-scroll">
              <div class="scroll-wrapper" id="reportsWrapper">
                <?php foreach($get_menu->result() as $m): ?>
                <div class="horizontal-card" data-report-title="<?php echo strtolower($m->menu); ?>">
                  <div class="report-card-horizontal">
                    <div class="report-title"><?php echo $m->menu; ?></div>
                    <button class="btn btn-sm btn-submit w-100" onclick="location.href='<?php echo base_url($m->uri); ?>'">
                      View
                    </button>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <button class="scroll-nav-btn scroll-right" data-target="reports-scroll">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Section 3: Kalendar Data -->
      <div class="container-fluid mb-4">
        <div class="horizontal-scroll-section mt-5" id="reportsSection">
          <div class="section-header">
            <h3><i class="fas fa-calendar me-2"></i> Calendar Data</h3>
          </div>

          <div class="row">
            <div class="col-md-12">
              <table id="table-calendar" style="width: 100%;" class="table-striped">
                <thead>
                  <tr>
                    <th style="width: 1%;">No</th>
                    <th>Branch</th>
                    <th>Subbranch</th>
                    <th>Region</th>
                    <th>Lastupload</th>
                    <th>Sales</th>
                    <th>Stock</th>
                    <th>File</th>
                    <th>Closing</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  foreach($get_kalendar->result() as $k): ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $k->branch_name ?></td>
                    <td><?php echo $k->nama_comp ?></td>
                    <td><?php echo $k->region ?></td>
                    <td><?php echo $k->lastupload ?></td>
                    <td><?php echo $k->tanggal."-".$k->bulan. "-".$k->tahun ?></td>
                    <td><?php echo $k->tanggal_stok ? $k->tanggal_stok : '-'; ?></td>
                    <td><?php echo $k->filename ?></td>
                    <td><?php echo $k->status_closing ? "Closing" : "-"; ?></td>
                  </tr>  
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal untuk Detail Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, var(--bs-primary), var(--bs-success)); color: white;">
        <h5 class="modal-title" id="attendanceModalLabel">
          <i class="fas fa-info-circle me-2"></i> Attendance Detail
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header bg-primary bg-opacity-10">
                <strong><i class="fas fa-user me-2"></i> User Information</strong>
              </div>
              <div class="card-body">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td width="35%"><strong>Username:</strong></td>
                    <td><span id="modalUsername">-</span></td>
                  </tr>
                  <tr>
                    <td><strong>Type:</strong></td>
                    <td><span id="modalType" class="badge bg-primary">-</span></td>
                  </tr>
                  <tr>
                    <td><strong>Time:</strong></td>
                    <td><span id="modalTime">-</span></td>
                  </tr>
                </table>
              </div>
            </div>
            
            <div class="card">
              <div class="card-header bg-success bg-opacity-10">
                <strong><i class="fas fa-map-pin me-2"></i> Location Details</strong>
              </div>
              <div class="card-body">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td width="35%"><strong>Latitude:</strong></td>
                    <td><span id="modalLat">-</span></td>
                  </tr>
                  <tr>
                    <td><strong>Longitude:</strong></td>
                    <td><span id="modalLng">-</span></td>
                  </tr>
                  <tr>
                    <td><strong>Address:</strong></td>
                    <td><span id="modalAddress">-</span></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-info bg-opacity-10">
                <strong><i class="fas fa-camera me-2"></i> Photo Evidence</strong>
              </div>
              <div class="card-body text-center">
                <div id="modalImageContainer">
                  <img id="modalImage" src="" alt="Attendance Photo" style="max-width: 100%; max-height: 300px; border-radius: 8px; display: none;">
                  <div id="noImageMsg" class="text-muted" style="display: none;">
                    <i class="fas fa-image fa-3x mb-2"></i>
                    <p>No photo available</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="openMapBtn" onclick="openInGoogleMaps()">
          <i class="fas fa-map-marked-alt me-1"></i> Open in Google Maps
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Smooth scroll untuk tombol Explore Dashboard
    $('a[href="#sections"]').on('click', function(e) {
      e.preventDefault();
      $('html, body').animate({
        scrollTop: $('#sections').offset().top
      }, 800);
    });
  
    // Menyesuaikan tinggi hero dengan navbar
    function adjustHeroHeight() {
      var navbarHeight = $('.navbar').outerHeight() || 70;
      $('.dashboard-hero').css('min-height', 'calc(100vh - ' + navbarHeight + 'px)');
    }
  
    adjustHeroHeight();
    $(window).on('resize', function() {
      adjustHeroHeight();
    });
  
    // Fungsi scroll horizontal dengan tombol navigasi
    function setupScrollButtons() {
      $('.scroll-nav-btn').off('click').on('click', function() {
        var targetId = $(this).data('target');
        var container = $('#' + targetId);
        var scrollAmount = container.width() * 0.7;
        
        if ($(this).hasClass('scroll-left')) {
          container.animate({
            scrollLeft: container.scrollLeft() - scrollAmount
          }, 250);
        } else if ($(this).hasClass('scroll-right')) {
          container.animate({
            scrollLeft: container.scrollLeft() + scrollAmount
          }, 250);
        }
      });
    }
    
    setupScrollButtons();
    
    function checkScrollButtons() {
      $('.scroll-wrapper-container').each(function() {
        var container = $(this).find('.scroll-container');
        var leftBtn = $(this).find('.scroll-left');
        var rightBtn = $(this).find('.scroll-right');
        
        if (container.length && container[0].scrollWidth <= container[0].clientWidth) {
          leftBtn.hide();
          rightBtn.hide();
        } else {
          leftBtn.show();
          rightBtn.show();
        }
      });
    }
    
    checkScrollButtons();
    $(window).on('resize', function() {
      checkScrollButtons();
    });
  
    $('.scroll-container').on('scroll', function() {
      var container = $(this);
      var parent = container.closest('.scroll-wrapper-container');
      var leftBtn = parent.find('.scroll-left');
      var rightBtn = parent.find('.scroll-right');
      
      if (container.scrollLeft() <= 0) {
        leftBtn.addClass('disabled').css('opacity', '0.5');
      } else {
        leftBtn.removeClass('disabled').css('opacity', '1');
      }
      
      if (container.scrollLeft() + container.innerWidth() >= container[0].scrollWidth - 1) {
        rightBtn.addClass('disabled').css('opacity', '0.5');
      } else {
        rightBtn.removeClass('disabled').css('opacity', '1');
      }
    });
    
    $('.scroll-container').trigger('scroll');
    
    // ========== SEARCH 1: TEAM MEMBERS & DAILY ACTIVITY ==========
    var searchTeamActivity = $('#searchTeamActivity');
    var clearTeamActivityBtn = $('#clearTeamActivityBtn');
    var teamActivityInfo = $('#teamActivityInfo');
    var teamCards = $('#teamMembersWrapper .horizontal-card');
    var activityItems = $('#activityList .activity-item');
    
    // Function to highlight text for team/activity
    function highlightTextTeamActivity(text, query) {
      if (!query || query.trim() === '') return text;
      var regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
      return text.replace(regex, '<span class="search-highlight">$1</span>');
    }
    
    // Function to update team & activity search
    function updateTeamActivitySearch() {
      var query = searchTeamActivity.val().trim().toLowerCase();
      var teamCount = 0;
      var activityCount = 0;
      
      if (query === '') {
        teamCards.show();
        activityItems.show();
        clearTeamActivityBtn.hide();
        teamActivityInfo.html('');
        
        teamCards.each(function() {
          var nameElem = $(this).find('.member-name');
          var originalName = nameElem.data('original-text') || nameElem.text();
          nameElem.data('original-text', originalName);
          nameElem.html(originalName);
        });
        
        activityItems.each(function() {
          var titleElem = $(this).find('.activity-title');
          var originalTitle = titleElem.data('original-text') || titleElem.text();
          titleElem.data('original-text', originalTitle);
          titleElem.html(originalTitle);
        });
        
        $('#teamEmptyState').remove();
        return;
      }
      
      clearTeamActivityBtn.show();
      
      teamCards.each(function() {
        var card = $(this);
        var username = card.data('username') || card.find('.member-name').text().toLowerCase();
        var match = username.indexOf(query) !== -1;
        
        if (match) {
          card.show();
          teamCount++;
          var nameElem = card.find('.member-name');
          var originalName = nameElem.data('original-text') || nameElem.text();
          nameElem.data('original-text', originalName);
          nameElem.html(highlightTextTeamActivity(originalName, query));
        } else {
          card.hide();
        }
      });
      
      activityItems.each(function() {
        var item = $(this);
        var username = item.data('username') || item.find('.activity-title').text().toLowerCase();
        var match = username.indexOf(query) !== -1;
        
        if (match) {
          item.show();
          activityCount++;
          var titleElem = item.find('.activity-title');
          var originalTitle = titleElem.data('original-text') || titleElem.text();
          titleElem.data('original-text', originalTitle);
          titleElem.html(highlightTextTeamActivity(originalTitle, query));
        } else {
          item.hide();
        }
      });
      
      if (teamCount === 0 && activityCount === 0) {
        teamActivityInfo.html('<i class="fas fa-info-circle"></i> No results found for "' + query + '"');
      } else {
        var resultsText = [];
        if (teamCount > 0) resultsText.push(teamCount + ' team member' + (teamCount > 1 ? 's' : ''));
        if (activityCount > 0) resultsText.push(activityCount + ' activit' + (activityCount > 1 ? 'ies' : 'y'));
        teamActivityInfo.html('<i class="fas fa-check-circle"></i> Found: ' + resultsText.join(', '));
      }
      
      var visibleCards = teamCards.filter(':visible');
      if (visibleCards.length === 0 && teamCards.length > 0) {
        if ($('#teamEmptyState').length === 0) {
          var emptyStateHtml = '<div id="teamEmptyState" class="empty-state" style="width: 100%; min-width: 300px; margin: 10px;">' +
            '<i class="fas fa-user-slash fa-3x"></i>' +
            '<h5>No matching members</h5>' +
            '<p>No team members found for "' + query + '"</p>' +
            '</div>';
          $('#teamMembersWrapper').append(emptyStateHtml);
        } else {
          $('#teamEmptyState').show();
          $('#teamEmptyState p').html('No team members found for "' + query + '"');
        }
      } else {
        $('#teamEmptyState').remove();
      }
    }
    
    // ========== SEARCH 2: REPORTS ==========
    var searchReports = $('#searchReports');
    var clearReportsBtn = $('#clearReportsBtn');
    var reportsInfo = $('#reportsInfo');
    var reportCards = $('#reportsWrapper .horizontal-card');
    
    function highlightTextReports(text, query) {
      if (!query || query.trim() === '') return text;
      var regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
      return text.replace(regex, '<span class="search-highlight">$1</span>');
    }
    
    function updateReportsSearch() {
      var query = searchReports.val().trim().toLowerCase();
      var reportCount = 0;
      
      if (query === '') {
        reportCards.show();
        clearReportsBtn.hide();
        reportsInfo.html('');
        
        reportCards.each(function() {
          var titleElem = $(this).find('.report-title');
          var originalTitle = titleElem.data('original-text') || titleElem.text();
          titleElem.data('original-text', originalTitle);
          titleElem.html(originalTitle);
        });
        
        $('#reportsEmptyState').remove();
        return;
      }
      
      clearReportsBtn.show();
      
      reportCards.each(function() {
        var card = $(this);
        var reportTitle = card.data('report-title') || card.find('.report-title').text().toLowerCase();
        var match = reportTitle.indexOf(query) !== -1;
        
        if (match) {
          card.show();
          reportCount++;
          var titleElem = card.find('.report-title');
          var originalTitle = titleElem.data('original-text') || titleElem.text();
          titleElem.data('original-text', originalTitle);
          titleElem.html(highlightTextReports(originalTitle, query));
        } else {
          card.hide();
        }
      });
      
      if (reportCount === 0) {
        reportsInfo.html('<i class="fas fa-info-circle"></i> No reports found for "' + query + '"');
      } else {
        reportsInfo.html('<i class="fas fa-check-circle"></i> Found: ' + reportCount + ' report' + (reportCount > 1 ? 's' : ''));
      }
      
      var visibleReports = reportCards.filter(':visible');
      if (visibleReports.length === 0 && reportCards.length > 0) {
        if ($('#reportsEmptyState').length === 0) {
          var reportsEmptyHtml = '<div id="reportsEmptyState" class="empty-state" style="width: 100%; min-width: 300px; margin: 10px;">' +
            '<i class="fas fa-file-alt fa-3x"></i>' +
            '<h5>No matching reports</h5>' +
            '<p>No reports found for "' + query + '"</p>' +
            '</div>';
          $('#reportsWrapper').append(reportsEmptyHtml);
        } else {
          $('#reportsEmptyState').show();
          $('#reportsEmptyState p').html('No reports found for "' + query + '"');
        }
      } else {
        $('#reportsEmptyState').remove();
      }
    }
    
    function debounce(func, wait) {
      var timeout;
      return function() {
        var context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
          func.apply(context, args);
        }, wait);
      };
    }
    
    searchTeamActivity.on('input', debounce(updateTeamActivitySearch, 200));
    searchReports.on('input', debounce(updateReportsSearch, 200));
    
    clearTeamActivityBtn.on('click', function() {
      searchTeamActivity.val('');
      updateTeamActivitySearch();
      searchTeamActivity.focus();
    });
    
    clearReportsBtn.on('click', function() {
      searchReports.val('');
      updateReportsSearch();
      searchReports.focus();
    });
    
    teamCards.each(function() {
      var nameElem = $(this).find('.member-name');
      nameElem.data('original-text', nameElem.text());
    });
    
    activityItems.each(function() {
      var titleElem = $(this).find('.activity-title');
      titleElem.data('original-text', titleElem.text());
    });
    
    reportCards.each(function() {
      var titleElem = $(this).find('.report-title');
      titleElem.data('original-text', titleElem.text());
    });
    
    var video = $('.hero-video-background')[0];
    if (video) {
      if (window.innerWidth < 768) {
        video.playbackRate = 0.8;
      }
      
      video.addEventListener('error', function() {
        $('.dashboard-hero').css('background', 'linear-gradient(135deg, var(--bs-primary), var(--bs-success))');
        $('.hero-video-background').hide();
      });
    }
  });

  var testVideo = document.createElement('video');
  if (!testVideo.canPlayType || testVideo.canPlayType('video/mp4') === '') {
    var hero = document.querySelector('.dashboard-hero');
    if (hero) {
      hero.style.background = 'linear-gradient(135deg, var(--bs-primary), var(--bs-success))';
      var videoElement = document.querySelector('.hero-video-background');
      if (videoElement) videoElement.remove();
    }
  }
</script>

<script>
  // Global variables untuk menyimpan data location
  var currentLat = null;
  var currentLng = null;

  $(document).ready(function () {
    $('#table-calendar').DataTable({
        "pageLength": 20,
        "ordering": false,
        "order": [0, 'desc'],
        "aLengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"]
        ],
        scrollX: true,
    });
    
    // Event handler untuk location value clicks
    $('.location-value').on('click', function() {
      var $this = $(this);
      
      // Ambil data dari attribute
      var username = $this.data('username');
      var type = $this.data('type');
      var lat = $this.data('lat');
      var lng = $this.data('lng');
      var address = $this.data('address');
      var imageUrl = $this.data('image');
      var time = $this.data('time');
      
      // Simpan ke global variable untuk Google Maps
      currentLat = lat;
      currentLng = lng;
      
      // Set nilai ke modal
      $('#modalUsername').text(username || '-');
      if (type === 'masuk') {
        $('#modalType').html('<span class="badge bg-success">Clock In</span>');
      } else {
        $('#modalType').html('<span class="badge bg-warning text-dark">Clock Out</span>');
      }
      $('#modalTime').text(time || '-');
      $('#modalLat').text(lat || '-');
      $('#modalLng').text(lng || '-');
      $('#modalAddress').text(address || '-');
      
      // Handle image - URL sudah lengkap dari model
      if (imageUrl && imageUrl !== '' && imageUrl !== null) {
        $('#modalImage').attr('src', imageUrl).show();
        $('#noImageMsg').hide();
        
        // Handle image load error
        $('#modalImage').off('error').on('error', function() {
          $(this).hide();
          $('#noImageMsg').show();
        });
      } else {
        $('#modalImage').hide();
        $('#noImageMsg').show();
      }
    });
  });
  
  // Function untuk membuka Google Maps
  function openInGoogleMaps() {
    if (currentLat && currentLng) {
      var url = 'https://www.google.com/maps?q=' + currentLat + ',' + currentLng;
      window.open(url, '_blank');
    } else {
      alert('Location coordinates not available');
    }
  }
</script>