<div class="az-content-left az-content-left-components">
    <div class="component-item">
        <label>Master Data</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'kpi/master_data#master-team-member' ?>" class="nav-link-new">Master Team Member</a>
            <a href="<?= base_url().'kpi/master_data#master-team-member-struktural' ?>" class="nav-link-new">Master Team
                Struktural</a>
            <a href="<?= base_url().'kpi/master_data#master-perhitungan' ?>" class="nav-link-new">Master Perhitungan</a>
            <a href="<?= base_url().'kpi/master_data#master-brand' ?>" class="nav-link-new">Master Brand</a>
        </nav>
        <label>Event & Activation</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'kpi#event' ?>" class="nav-link-new">Input Event Yang Terlaksana</a>
            <a href="<?= base_url().'kpi/verifikasi_event/list' ?>" class="nav-link-new" target=_blank>Verifikasi
                Event</a>
            <a href="<?= base_url().'kpi/generate_report' ?>" class="nav-link-new" target=_blank>Generate Report</a>
        </nav>
        <label>Spreading</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'kpi#pemerataan' ?>" class="nav-link-new">Pemerataan Product Non OB DP</a>
            <a href="<?= base_url().'kpi/verifikasi_pemerataan/list' ?>" class="nav-link-new">Verifikasi Pemerataan Product Non OB DP</a>
            <a href="<?= base_url().'kpi/dashboard_pemerataan_product' ?>" class="nav-link-new" target="_blank">Dashboard Spreading (Pemerataan Product)</a>
            <br>
            <a href="<?= base_url().'kpi#visibility' ?>" class="nav-link-new">Visibility / Branding di OB DP</a>
            <a href="<?= base_url().'kpi/verifikasi_visibility/list' ?>" class="nav-link-new">Verifikasi Visibility / Branding di OB DP</a>
            <a href="<?= base_url().'kpi/dashboard_visibility' ?>" class="nav-link-new" target="_blank">Dashboard Spreading (Visibility)</a>
        </nav>
        <label>Surveyor</label>
        <nav class="nav flex-column">
            <a href="<?= base_url().'kpi#market_survey' ?>" class="nav-link-new">Market Survey</a>
            <a href="<?= base_url().'kpi/verifikasi_market_survey/list' ?>" class="nav-link-new">Verifikasi Survey</a>
            <a href="<?= base_url().'kpi/dashboard_surveyor' ?>" class="nav-link-new" target="_blank">Dashboard Surveyor</a>
        </nav>
    </div>
</div>