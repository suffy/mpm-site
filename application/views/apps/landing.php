    <!-- GT Section -->

    <section id="gt-section" class="section section-1">
    <div class="container">
        <div class="row align-items-center g-4">
            <!-- Content Section Column -->
            <div class="col-lg-6 order-1 order-lg-1">
                <div class="content-section slide-in-left">
                    <h1 class="main-title mb-4">
                        <span class="highlight">Deltomed</span> GT Dashboard
                    </h1>
                    
                    <p class="description mb-5">
                        Comprehensive control panel for managing all GT-related data and activities in the MPM ecosystem. 
                        Monitor performance, track progress, and optimize your workflow with real-time insights.
                    </p>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <!-- <button class="btn btn-primary-custom btn-custom" onclick="scrollToSection('mt-section')">
                            Explore MT Dashboard <i class="fas fa-arrow-down"></i>
                        </button> -->
                        <a href="<?= base_url().'apps/gt_setup_pasar' ?>" target="_blank">
                        <button class="btn btn-secondary-custom btn-custom">
                            Setup GT PASAR <i class="fas fa-gear"></i>
                        </button>
                        </a>
                    </div>
                    
                    <!-- Quick Stats Panel (hidden by default) -->
                    <!-- <div id="quickStatsPanel" class="mt-4 p-4 rounded-3" style="background: rgba(255,255,255,0.9); display: none;">
                        <h5 class="mb-3 text-primary"><i class="fas fa-tachometer-alt me-2"></i>Today's Overview</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted">Market Visits</small>
                                    <h4 class="mb-0">24 <small class="text-success">+5%</small></h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted">Events Completed</small>
                                    <h4 class="mb-0">8 <small class="text-success">+12%</small></h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted">Branding Updated</small>
                                    <h4 class="mb-0">15 <small class="text-warning">-2%</small></h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <small class="text-muted">Join Calls</small>
                                    <h4 class="mb-0">7 <small class="text-success">+8%</small></h4>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- Services Grid Column -->
            <div class="col-lg-6 order-2 order-lg-2">
                <div class="services-grid slide-in-right">
                    <div class="row g-0">
                        <!-- Market Audit -->
                        <div class="col-md-6">
                            <div class="service-box web-design d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in delay-1">
                                <a href="<?= base_url() ?>apps/<?= $url_market_audit?>" target="_blank" style:="text-decoration: none;">
                                    
                                    <div class="service-icon-container">
                                        <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <!-- <div class="service-badge">New</div> -->
                                    </div>
                                    <h3 class="service-title">GT Market Audit</h3>
                                    <p class="service-desc">Track and manage store visits and activities</p>
                                    <div class="service-progress mt-2">
                                        <!-- <div class="progress" style="height: 4px;">
                                            <div class="progress-bar" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> -->
                                        <!-- <small class="text-muted">85% completion this week</small> -->
                                    </div>
                                    
                                </a>
                            </div>
                        </div>
                        
                        <!-- Realisasi Event -->
                        <div class="col-md-6">
                            <div class="service-box development d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in delay-1">
                                <a href="<?= base_url() ?>apps/<?= $url_realisasi_event ?>" target="_blank">
                                    <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                        <circle cx="8" cy="14" r="2"></circle>
                                        <circle cx="16" cy="16" r="1"></circle>
                                    </svg>
                                    <h3 class="service-title">GT Realisasi Event</h3>
                                    <p class="service-desc">Manage and track marketing events</p>
                                    <!-- <div class="service-stats mt-2">
                                        <span class="badge bg-warning text-dark"><i class="fas fa-calendar-day me-1"></i> 3 upcoming</span>
                                    </div> -->
                                </a>
                            </div>
                        </div>
                        
                        <!-- Branding -->
                        <div class="col-md-6">
                            <div class="service-box marketing d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in delay-2">
                                <a href="<?= base_url() ?>apps/<?= $url_branding_delto_corner ?>" target="_blank">
                                    <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    <h3 class="service-title">GT Branding</h3>
                                    <p class="service-desc">Manage Delto Corner branding</p>
                                    <!-- <div class="service-stats mt-2">
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> 92% compliant</span>
                                    </div> -->
                                </a>
                            </div>
                        </div>
                        
                        <!-- Spreading -->
                        <div class="col-md-6">
                            <div class="service-box support d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in delay-2">
                                <a href="<?= base_url() ?>apps/<?= $url_spreading ?>" target="_blank">
                                    <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="18" cy="5" r="3"></circle>
                                        <circle cx="6" cy="12" r="3"></circle>
                                        <circle cx="18" cy="19" r="3"></circle>
                                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                    </svg>
                                    <h3 class="service-title">GT Spreading</h3>
                                    <p class="service-desc">Product distribution tracking</p>
                                    <!-- <div class="service-stats mt-2">
                                        <span class="badge bg-info"><i class="fas fa-boxes me-1"></i> 245 placements</span>
                                    </div> -->
                                </a>
                            </div>
                        </div>

                        <!-- Join Call -->
                        <div class="col-md-12">
                            <div class="service-box management d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in delay-3">
                                <a href="<?= base_url() ?>apps/<?= $url_join_call ?>" target="_blank">
                                    <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <h3 class="service-title">GT Join Call</h3>
                                    <p class="service-desc">Market Visit tracking</p>
                                    <!-- <div class="service-stats mt-2">
                                        <span class="badge bg-primary"><i class="fas fa-phone-alt me-1"></i> 12 today</span>
                                    </div> -->
                                </a>
                            </div>
                        </div>
                        
                        <!-- Performance -->
                        <!-- <div class="col-md-6">
                            <div class="service-box management d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in delay-3">
                                <a href="#" onclick="showComingSoon()">
                                    <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="service-title">GT Performance</h3>
                                    <p class="service-desc">Team analytics & KPIs</p>
                                    <div class="service-stats mt-2">
                                        <span class="badge bg-secondary">Coming Soon</span>
                                    </div>
                                </a>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator" onclick="scrollToSection('mt-section')">
            <svg class="scroll-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- MT Section -->
    <section id="mt-section" class="section section-3">
        <div class="container">
            <div class="row align-items-center g-4">
                <!-- Services Grid Column -->
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="services-grid slide-in-left">
                        <div class="row g-0">
                            <div class="col-12">
                                <div class="service-box management d-flex flex-column align-items-center justify-content-center text-center h-100 fade-in">
                                    <a href="<?= base_url() ?>apps/<?= $url_mt_activity ?>" target="_blank">
                                        <svg class="service-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <h3 class="service-title">MT Activity</h3>
                                        <p class="service-desc">Monitor MT Team field activities</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Section Column -->
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="content-section slide-in-right">
                        <h1 class="main-title mb-4">
                            <span class="highlight">Deltomed</span> MT Dashboard
                        </h1>
                        
                        <p class="description mb-5">
                            Specialized control panel for Medical Team activities and performance tracking. 
                            Get real-time insights and comprehensive reports on MT operations.
                        </p>
                        
                        <div class="d-flex gap-3 flex-wrap">
                            <button class="btn btn-primary-custom btn-custom" onclick="scrollToSection('stats-section')">
                                View Statistics <i class="fas fa-chart-bar"></i>
                            </button>
                            <button class="btn btn-secondary-custom btn-custom" onclick="scrollToSection('gt-section')">
                                Back to GT <i class="fas fa-arrow-up"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator" onclick="scrollToSection('stats-section')">
            <svg class="scroll-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats-section" class="section section-2">
        <div class="container">
            <div class="row align-items-center g-4">
                <!-- Stats Grid Column -->
                <div class="col-lg-6 order-1 order-lg-1">
                    <div class="stats-grid slide-in-left">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="stat-item fade-in delay-1">
                                    <a href="<?= base_url() ?>apps/<?= $url_mpm_market_visit ?>">
                                        <i class="fas fa-map-marked-alt stat-icon"></i>
                                        <div class="stat-label">Market Visit</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-item fade-in delay-1">
                                    <a>
                                        <i class="fas fa-users stat-icon"></i>
                                        <div class="stat-label">Attendance Team (coming soon)</div>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="stat-item fade-in delay-2">
                                    <a href="<?= base_url() ?>apps/<?= $url_mpm_activity ?>">
                                        <i class="fas fa-tools stat-icon"></i>
                                        <div class="stat-label">MPM Activity</div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-item fade-in delay-2">
                                    <a href="<?= base_url() ?>apps/<?= $url_attendance_setup ?>">
                                        <i class="fas fa-gear stat-icon"></i>
                                        <div class="stat-label">Attendance Setup</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Section Column -->
                <div class="col-lg-6 order-2 order-lg-2">
                    <div class="content-section slide-in-right">
                        <h1 class="main-title mb-4">
                            MPM <span class="highlight">Analytics</span> Dashboard
                        </h1>
                        
                        <p class="description mb-5">
                            Integrated real-time analytics platform connected directly with MPM-Apps. 
                            Get comprehensive insights, generate reports, and make data-driven decisions.
                        </p>
                        
                        <div class="d-flex gap-3 flex-wrap">
                            <button class="btn btn-secondary-custom btn-custom" onclick="scrollToSection('gt-section')">
                                Back to GT <i class="fas fa-arrow-up"></i>
                            </button>
                            <button class="btn btn-primary-custom btn-custom">
                                Generate Report <i class="fas fa-file-pdf"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script> -->
    <script>
        // Smooth scroll function
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });
            
            // Update active nav dot
            document.querySelectorAll('.nav-dot').forEach(dot => {
                dot.classList.remove('active');
            });
            
            if (sectionId === 'gt-section') {
                document.querySelectorAll('.nav-dot')[0].classList.add('active');
            } else if (sectionId === 'mt-section') {
                document.querySelectorAll('.nav-dot')[1].classList.add('active');
            } else if (sectionId === 'stats-section') {
                document.querySelectorAll('.nav-dot')[2].classList.add('active');
            }
        }

        // Add click animation to service boxes
        document.querySelectorAll('.service-box').forEach(box => {
            box.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-8px)';
                }, 150);
            });
        });

        // Enhanced button animations
        document.querySelectorAll('.btn-custom').forEach(btn => {
            btn.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.95)';
            });
            
            btn.addEventListener('mouseup', function() {
                this.style.transform = 'translateY(-3px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });

        // Update active nav dot based on scroll position
        function updateActiveNavDot() {
            const sections = document.querySelectorAll('.section');
            let currentSection = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (window.scrollY >= sectionTop - 200 && window.scrollY < sectionTop + sectionHeight - 200) {
                    currentSection = section.id;
                }
            });
            
            document.querySelectorAll('.nav-dot').forEach(dot => {
                dot.classList.remove('active');
            });
            
            if (currentSection === 'gt-section') {
                document.querySelectorAll('.nav-dot')[0].classList.add('active');
            } else if (currentSection === 'mt-section') {
                document.querySelectorAll('.nav-dot')[1].classList.add('active');
            } else if (currentSection === 'stats-section') {
                document.querySelectorAll('.nav-dot')[2].classList.add('active');
            }
        }

        // Add animation classes when elements come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add(entry.target.dataset.animation);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('[data-animation]').forEach(el => {
            observer.observe(el);
        });

        // Listen for scroll events
        window.addEventListener('scroll', () => {
            updateActiveNavDot();
        });
        
        // Check on load
        window.addEventListener('load', () => {
            updateActiveNavDot();
        });
    </script>

    <script>
        // Add these new functions to your existing script section
        function showQuickStats() {
            const panel = document.getElementById('quickStatsPanel');
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
        
        function showComingSoon() {
            alert('This feature is coming soon! Stay tuned for updates.');
        }
    </script>


</body>
</html>