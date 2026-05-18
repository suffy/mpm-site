    <style>
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } */

        /* body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        } */

        /* .container {
            display: flex;
            min-height: 100vh;
        } */

        /* Sidebar */
        /* .sidebar {
            width: 200px;
            background-color: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        } */

        /* .logo {
            padding: 0 20px 30px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
        } */

        /* .logo::before {
            content: "○";
            margin-right: 8px;
            color: #666;
        } */

        /* .nav-menu {
            list-style: none;
        } */

        /* .nav-item {
            padding: 8px 20px;
            cursor: pointer;
            transition: background-color 0.2s;
        } */

        /* .nav-item:hover {
            background-color: #f5f5f5;
        } */

        /* .nav-item.active {
            background-color: #f0f0f0;
            font-weight: 500;
        } */

        /* .nav-section {
            margin-top: 30px;
            padding: 0 20px;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 10px;
        } */

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .quick-create {
            background-color: #333;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        /* Metrics Grid */
        .metrics-grid {
            /* display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px; */
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        @media (max-width: 991px) {
            .metrics-grid {
                flex-direction: column;
            }
        }

        .metric-card {
            /* background: white; */
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .metric-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 14px;
            /* color: #666; */
            color: var(--bs-dark-text-emphasis);
            margin-bottom: 10px;
        }

        .metric-change {
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .metric-change.positive {
            color: #10b981;
        }

        .metric-change.negative {
            color: #ef4444;
        }

        .metric-description {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        /* Chart Section */
        .chart-section {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            margin-bottom: 30px;
        }

        .chart-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
        }

        .chart-subtitle {
            font-size: 14px;
            color: #666;
        }

        .time-filters {
            display: flex;
            gap: 10px;
        }

        .time-filter {
            padding: 6px 12px;
            border: 1px solid #e0e0e0;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .time-filter.active {
            background: #f0f0f0;
        }

        .chart-container {
            padding: 20px;
            height: 300px;
            position: relative;
        }

        .chart-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%), 
                        linear-gradient(-45deg, #f0f0f0 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #f0f0f0 75%), 
                        linear-gradient(-45deg, transparent 75%, #f0f0f0 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
        }

        /* Document Table */
        .document-section {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .tab {
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }

        .tab.active {
            background: #f0f0f0;
        }

        .tab-badge {
            background: #666;
            color: white;
            border-radius: 12px;
            padding: 2px 6px;
            font-size: 11px;
            margin-left: 5px;
        }
    </style>
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">Rp. <?php echo number_format($get_total_omzet, 0, ',', '.'); ?></div>
                <div class="metric-label">Total Sales This Month</div>
                <div class="metric-change positive">Last Updated : <?= $created_at ?></div>
                <div class="metric-description mt-4"><a class="btn btn-success btn-sm outline" target="_blank" href="<?php echo base_url().'sales_omzet/omzet'; ?>" style="font-size: 14px; padding: 8px 5px 2px 5px; text-align: center; width: 100%;">Link to Omzet DP</a></div>
            </div>
        
            <div class="metric-card">
                <div class="metric-value">Rp. <?php echo number_format($get_total_po, 0, ',', '.'); ?></div>
                <div class="metric-label">Total PO This Month</div>
                <div class="metric-change positive">Last Update : <?= $created_at ?></div>
                <div class="metric-description mt-4"><a class="btn btn-primary btn-sm outline" target="_blank" href="<?php echo base_url().'spk/list_order'; ?>" style="font-size: 14px; padding: 8px 5px 2px 5px; text-align: center; width: 100%;">Link to List Order</a></div>
            </div>
            <div class="metric-card">
                <div class="metric-value">DOI By Principal</div>
                <?php 
                    foreach ($summary_doi as $key) { ?>
                        <div class="metric-change"><?= $key->namasupp." : ".number_format($key->doi, 2, ',', '.'); ?></div>
                    <?php
                    }
                ?>
                
                <div class="metric-description mt-4">
                <?php 
                    if($supp == '000'){ ?>        
                        <a class="btn btn-warning btn-sm outline mt-3" target="_blank" href="<?php echo base_url().'management_office/export_doi'; ?>" style="font-size: 14px; padding: 8px 5px 2px 5px; text-align: center; width: 100%;">Export Data</a>
                    <?php
                    }else{ ?>
                    
                        <div class="metric-label">&nbsp;</div>
                        <div class="metric-change positive mt-2">&nbsp;</div>
                        <a class="btn btn-dark btn-sm outline" style="font-size: 14px; padding: 8px 5px 2px 5px; text-align: center; width: 100%;">belum tersedia untuk user anda</a>
                    <?php
                    } ?>
                </div>
            
            </div>

            <div class="metric-card">
                <div class="metric-value">Total AR : <?php echo number_format($total_ar, 0, ',', '.'); ?></div>
                
                <div class="metric-description mt-4">
                <?php 
                    if($supp == '000'){ ?>        
                        <div class="metric-label">Count Company : <?= $count ?></div>
                        <div class="metric-change positive mt-2">Last Update : <?= $created_at ?></div>
                        <a class="btn btn-danger btn-sm outline" target="_blank" href="<?php echo base_url().'spk/analisa_piutang'; ?>" style="font-size: 14px; padding: 8px 5px 2px 5px; text-align: center; width: 100%;">Link to Analisa Piutang</a>
                    <?php
                    }else{ ?>
                        <div class="metric-label">&nbsp;</div>
                        <div class="metric-change positive mt-2">&nbsp;</div>
                        <a class="btn btn-dark btn-sm outline" style="font-size: 14px; padding: 8px 5px 2px 5px; text-align: center; width: 100%;">belum tersedia untuk user anda</a>
                    <?php
                    } ?>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <!-- <div class="chart-section">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Total Visitors</div>
                    <div class="chart-subtitle">Total for the last 3 months</div>
                </div>
                <div class="time-filters">
                    <div class="time-filter active">Last 3 months</div>
                    <div class="time-filter">Last 30 days</div>
                    <div class="time-filter">Last 7 days</div>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-placeholder">Chart visualization would appear here</div>
            </div>
        </div> -->

        <!-- Document Table -->
        <div class="document-section">
            <!-- <div class="table-header">
                <div class="tab active">Outline</div>
                <div class="tab">Past Performance <span class="tab-badge">3</span></div>
                <div class="tab">Key Personnel <span class="tab-badge">2</span></div>
                <div class="tab">Focus Documents</div>
                <div class="table-actions">
                    <button class="action-btn">Customize Columns</button>
                    <button class="action-btn">+ Add Section</button>
                </div>
            </div> -->

            <!-- <table id="tabel2" class="table-striped" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Subbranch</th>
                        <th>Tanggal Faktur</th>
                        <th>LastUpload</th>
                        <th>StatusClosing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get_kalender_by_bulan as $p) : ?>
                    <tr>
                        <td><?= $p->branch_name ?></td>
                        <td><?= $p->nama_comp ?></td>
                        <td><?= $p->tanggal ?></td>
                        <td><?= $p->lastupload ? date('d M y', strtotime($p->lastupload)) : 'Belum Upload'; ?></td>
                        <td><?= $p->status_closing; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> -->

            <!-- <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Header</th>
                        <th>Section Type</th>
                        <th>Status</th>
                        <th>Target</th>
                        <th>Limit</th>
                        <th>Reviewer</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#</td>
                        <td>Cover page</td>
                        <td>Cover page</td>
                        <td><span class="status in-progress">In Progress</span></td>
                        <td>18</td>
                        <td>5</td>
                        <td>Eddie Lake</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Table of contents</td>
                        <td>Table of contents</td>
                        <td><span class="status done">Done</span></td>
                        <td>29</td>
                        <td>24</td>
                        <td>Eddie Lake</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Executive summary</td>
                        <td>Narrative</td>
                        <td><span class="status done">Done</span></td>
                        <td>10</td>
                        <td>13</td>
                        <td>Eddie Lake</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Technical approach</td>
                        <td>Narrative</td>
                        <td><span class="status done">Done</span></td>
                        <td>27</td>
                        <td>23</td>
                        <td>Jamik Tashpulatov</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Design</td>
                        <td>Narrative</td>
                        <td><span class="status in-progress">In Progress</span></td>
                        <td>2</td>
                        <td>16</td>
                        <td>Jamik Tashpulatov</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Capabilities</td>
                        <td>Narrative</td>
                        <td><span class="status in-progress">In Progress</span></td>
                        <td>20</td>
                        <td>8</td>
                        <td>Jamik Tashpulatov</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Integration with existing systems</td>
                        <td>Narrative</td>
                        <td><span class="status in-progress">In Progress</span></td>
                        <td>19</td>
                        <td>21</td>
                        <td>Jamik Tashpulatov</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Innovation and Advantages</td>
                        <td>Narrative</td>
                        <td><span class="status done">Done</span></td>
                        <td>25</td>
                        <td>26</td>
                        <td>Assign reviewer</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Overview of EMR's Innovative Solutions</td>
                        <td>Technical content</td>
                        <td><span class="status done">Done</span></td>
                        <td>7</td>
                        <td>23</td>
                        <td>Assign reviewer</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                    <tr>
                        <td>#</td>
                        <td>Advanced Algorithms and Machine Learning</td>
                        <td>Narrative</td>
                        <td><span class="status done">Done</span></td>
                        <td>30</td>
                        <td>28</td>
                        <td>Assign reviewer</td>
                        <td class="more-actions">⋯</td>
                    </tr>
                </tbody>
            </table> -->

            <!-- <div class="pagination">
                <div class="pagination-info">0 of 68 row(s) selected. | Rows per page 10 | Page 1 of 7</div>
                <div class="pagination-controls">
                    <button class="page-btn">‹‹</button>
                    <button class="page-btn">‹</button>
                    <button class="page-btn">›</button>
                    <button class="page-btn">››</button>
                </div>
            </div> -->
        </div>
    </div>
</div>
